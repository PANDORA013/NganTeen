<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configure Intervention Image with robust error handling
        try {
            // Coba konfigurasi GD driver dulu
            if (extension_loaded('gd')) {
                \Intervention\Image\ImageManagerStatic::configure(['driver' => 'gd']);
                Log::info('Intervention Image configured with GD driver');
            } elseif (extension_loaded('imagick')) {
                \Intervention\Image\ImageManagerStatic::configure(['driver' => 'imagick']);
                Log::info('Intervention Image configured with Imagick driver');
            } else {
                Log::warning('No image processing extensions found (GD or Imagick)');
            }
        } catch (\Exception $e) {
            Log::error('Failed to configure Intervention Image', [
                'error' => $e->getMessage(),
                'gd_loaded' => extension_loaded('gd'),
                'imagick_loaded' => extension_loaded('imagick')
            ]);
        }
    }
}