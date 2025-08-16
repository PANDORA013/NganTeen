<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Intervention\Image\ImageManagerStatic as Image;

class ProfileController extends Controller
{
    /**
     * Menampilkan form profil pengguna
     * 
     * @param Request $request
     * @return View
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna
     * 
     * @param ProfileUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        // Proses upload foto profil
        if ($request->hasFile('profile_photo')) {
            try {
                // Hapus foto lama jika ada
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }

                // Simpan foto baru
                $path = $request->file('profile_photo')->store('profile_photos', 'public');

                // Resize gambar max width 300px sambil menjaga rasio
                $image = Image::make(storage_path('app/public/' . $path));
                $image->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $image->save();

                // Update path di DB
                $user->profile_photo = $path;
            } catch (\Exception $e) {
                return back()->withErrors(['profile_photo' => 'Gagal mengunggah foto profil. ' . $e->getMessage()]);
            }
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Upload QRIS image for seller
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function uploadQris(Request $request): RedirectResponse
    {
        $request->validate([
            'qris_image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $user = $request->user();
        
        try {
            // Delete old QRIS image if exists
            if ($user->qris_image) {
                Storage::disk('public')->delete($user->qris_image);
            }

            // Store new QRIS image
            $imagePath = $request->file('qris_image')->store('qris', 'public');
            
            // Resize image to max 500px width while maintaining aspect ratio
            $image = Image::make(storage_path('app/public/' . $imagePath));
            $image->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save();

            // Update user with new QRIS image path
            $user->update(['qris_image' => $imagePath]);

            return back()->with('status', 'qris-uploaded');
        } catch (\Exception $e) {
            return back()->withErrors(['qris_image' => 'Gagal mengunggah gambar QRIS. ' . $e->getMessage()]);
        }
    }

    /**
     * Delete QRIS image for seller
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteQris(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        if ($user->qris_image) {
            Storage::disk('public')->delete($user->qris_image);
            $user->update(['qris_image' => null]);
            return back()->with('status', 'qris-deleted');
        }
        
        return back()->with('status', 'no-qris-found');
    }

    /**
     * Menghapus akun pengguna
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        try {
            // Clean up user-related files before deleting account
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            
            if ($user->qris_image) {
                Storage::disk('public')->delete($user->qris_image);
            }

            // Log the account deletion for audit purposes
            Log::info('User account deleted', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'deleted_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Logout the user first
            Auth::logout();

            // Delete the user account
            $user->delete();

            // Invalidate and regenerate session for security
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/')->with('success', 'Akun Anda telah berhasil dihapus. Terima kasih telah menggunakan layanan kami.');
            
        } catch (\Exception $e) {
            Log::error('Error during account deletion', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus akun. Silakan coba lagi.']);
        }
    }
}
