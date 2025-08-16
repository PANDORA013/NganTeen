<?php

namespace App\Console\Commands;

use App\Events\NewMenuAdded;
use App\Events\OrderStatusUpdated;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Console\Command;

class TestBroadcasting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:broadcasting {type=all : Type of test (menu|order|all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test broadcasting events for real-time notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');

        switch ($type) {
            case 'menu':
                $this->testMenuBroadcast();
                break;
            case 'order':
                $this->testOrderBroadcast();
                break;
            case 'all':
                $this->testMenuBroadcast();
                $this->testOrderBroadcast();
                break;
            default:
                $this->error('Invalid test type. Use: menu, order, or all');
                return 1;
        }

        return 0;
    }

    private function testMenuBroadcast()
    {
        $this->info('Testing Menu Broadcasting...');

        // Get a random menu or create one for testing
        $menu = Menu::with('user')->first();
        
        if (!$menu) {
            $this->warn('No menu found. Creating a test menu...');
            
            $user = User::where('role', 'penjual')->first();
            if (!$user) {
                $this->error('No seller user found. Please create a seller user first.');
                return;
            }

            $menu = Menu::create([
                'user_id' => $user->id,
                'nama_menu' => 'Test Menu - ' . now()->format('H:i:s'),
                'harga' => 15000,
                'stok' => 10,
                'area_kampus' => 'Kampus A',
                'nama_warung' => 'Test Warung'
            ]);
        }

        // Manually trigger the event
        event(new NewMenuAdded($menu));
        
        $this->info("✅ NewMenuAdded event broadcasted for menu: {$menu->nama_menu}");
        $this->line("   Channel: menu.updates");
        $this->line("   Event: menu.added");
    }

    private function testOrderBroadcast()
    {
        $this->info('Testing Order Broadcasting...');

        // Get a random order or create a fake one for testing
        $order = Order::with('user')->first();
        
        if (!$order) {
            $this->warn('No order found. Creating a fake test order...');
            
            $user = User::where('role', 'pembeli')->first();
            if (!$user) {
                $this->error('No buyer user found. Please create a buyer user first.');
                return;
            }

            // Create a fake order object without saving to database
            $order = new Order([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_harga' => 25000
            ]);
            $order->id = 9999; // Fake ID
            $order->setRelation('user', $user);
        }

        $oldStatus = $order->status;
        $newStatus = $oldStatus === 'pending' ? 'ready' : 'pending';

        // Manually trigger the event
        event(new OrderStatusUpdated($order, $oldStatus, $newStatus));
        
        $this->info("✅ OrderStatusUpdated event broadcasted for order ID: {$order->id}");
        $this->line("   Channel: user.{$order->user_id}");
        $this->line("   Event: order.status.updated");
        $this->line("   Status: {$oldStatus} → {$newStatus}");
    }
}
