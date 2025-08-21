<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warung;
use App\Models\GlobalOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display user management page
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Apply filters
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            if ($request->status === 'online') {
                $query->where('is_online', true);
            } elseif ($request->status === 'offline') {
                $query->where('is_online', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $users = $query->with(['warung'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(15);

        // Calculate statistics
        $stats = [
            'total_users' => User::count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'penjual_users' => User::where('role', 'penjual')->count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'active_today' => User::whereDate('last_login_at', today())->count(),
            'active_warungs' => \App\Models\Warung::count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show user details
     */
    public function show(User $user)
    {
        $user->load(['warung', 'orders']);
        
        $userStats = [
            'total_orders' => $user->orders->count(),
            'total_spent' => $user->orders->where('payment_status', 'paid')->sum('total_amount'),
            'last_order' => $user->orders->latest()->first(),
            'registration_days' => $user->created_at->diffInDays(now()),
        ];

        if ($user->role === 'penjual' && $user->warung) {
            $warungStats = [
                'total_revenue' => GlobalOrder::where('warung_id', $user->warung->id)
                                            ->where('payment_status', 'paid')
                                            ->sum('net_amount'),
                'total_orders' => GlobalOrder::where('warung_id', $user->warung->id)->count(),
                'pending_settlement' => GlobalOrder::where('warung_id', $user->warung->id)
                                                  ->where('payment_status', 'paid')
                                                  ->where('is_settled', false)
                                                  ->sum('net_amount'),
            ];
        } else {
            $warungStats = null;
        }

        return view('admin.users.show', compact('user', 'userStats', 'warungStats'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,pembeli,penjual',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,pembeli,penjual',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete admin user'
            ]);
        }

        if ($user->role === 'penjual' && $user->warung) {
            // Check if warung has pending orders
            $pendingOrders = GlobalOrder::where('warung_id', $user->warung->id)
                                       ->whereIn('payment_status', ['pending', 'paid'])
                                       ->where('is_settled', false)
                                       ->count();

            if ($pendingOrders > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete user. Warung has {$pendingOrders} pending orders/settlements."
                ]);
            }
        }

        try {
            $userName = $user->name;
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => "User '{$userName}' has been deleted successfully."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Toggle user status (activate/deactivate)
     */
    public function toggleStatus(User $user)
    {
        try {
            $user->is_active = !$user->is_active;
            $user->save();

            $status = $user->is_active ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => "User has been {$status} successfully.",
                'is_active' => $user->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user status: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Bulk delete users
     */
    public function bulkDelete(Request $request)
    {
        $userIds = $request->input('user_ids', []);
        
        if (empty($userIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No users selected for deletion.'
            ]);
        }

        $users = User::whereIn('id', $userIds)->get();
        $deletedCount = 0;
        $errors = [];

        foreach ($users as $user) {
            if ($user->role === 'admin') {
                $errors[] = "Cannot delete admin user: {$user->name}";
                continue;
            }

            if ($user->role === 'penjual' && $user->warung) {
                $pendingOrders = GlobalOrder::where('warung_id', $user->warung->id)
                                           ->whereIn('payment_status', ['pending', 'paid'])
                                           ->where('is_settled', false)
                                           ->count();

                if ($pendingOrders > 0) {
                    $errors[] = "Cannot delete {$user->name}: Warung has {$pendingOrders} pending orders";
                    continue;
                }
            }

            try {
                $user->delete();
                $deletedCount++;
            } catch (\Exception $e) {
                $errors[] = "Failed to delete {$user->name}: " . $e->getMessage();
            }
        }

        $message = "Successfully deleted {$deletedCount} users.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', $errors);
        }

        return response()->json([
            'success' => $deletedCount > 0,
            'message' => $message,
            'deleted_count' => $deletedCount
        ]);
    }
}
