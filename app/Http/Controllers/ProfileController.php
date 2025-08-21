<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $user = $request->user();
            $user->fill($request->validated());

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            // Handle profile photo upload
            $profilePhotoPath = $this->handleProfilePhotoUpload($request);
            if ($profilePhotoPath) {
                // Delete old photo if exists
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                $user->profile_photo = $profilePhotoPath;
            }

            $user->save();

            return Redirect::route('profile.edit')->with('status', 'profile-updated');

        } catch (\Exception $e) {
            Log::error('Profile update failed', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);

            return Redirect::route('profile.edit')
                ->withErrors(['error' => 'Gagal memperbarui profil: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete profile photo if exists
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Upload QRIS image (penjual only)
     */
    public function uploadQris(Request $request): RedirectResponse
    {
        $request->validate([
            'qris_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $user = Auth::user();
            
            // Delete old QRIS if exists
            if ($user->qris_image) {
                Storage::disk('public')->delete($user->qris_image);
            }
            
            // Store new QRIS
            $path = $request->file('qris_image')->store('qris', 'public');
            \App\Models\User::where('id', $user->id)->update(['qris_image' => $path]);
            
            return back()->with('success', 'QRIS berhasil diunggah!');
            
        } catch (\Exception $e) {
            Log::error('QRIS upload failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            return back()->withErrors(['qris_image' => 'Gagal mengunggah QRIS: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete QRIS image
     */
    public function deleteQris(): RedirectResponse
    {
        try {
            $user = Auth::user();
            
            if ($user->qris_image) {
                Storage::disk('public')->delete($user->qris_image);
                \App\Models\User::where('id', $user->id)->update(['qris_image' => null]);
            }
            
            return back()->with('success', 'QRIS berhasil dihapus!');
            
        } catch (\Exception $e) {
            Log::error('QRIS deletion failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            return back()->withErrors(['error' => 'Gagal menghapus QRIS: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the password change form.
     */
    public function editPassword(Request $request): View
    {
        return view('profile.password', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'password_updated_at' => now(),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Display the QRIS management form (Penjual only).
     */
    public function editQris(Request $request): View
    {
        if (!$request->user()->isPenjual()) {
            abort(403, 'Access denied. Penjual role required.');
        }

        return view('profile.qris', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Handle profile photo upload with multiple fallback methods
     */
    private function handleProfilePhotoUpload(Request $request): ?string
    {
        if (!$request->hasFile('profile_photo')) {
            return null;
        }

        $file = $request->file('profile_photo');
        
        if (!$file->isValid()) {
            throw new \InvalidArgumentException('File upload tidak valid');
        }

        return $this->processAndStoreImage($file);
    }

    /**
     * Process and store image with triple protection system
     */
    private function processAndStoreImage(UploadedFile $file): string
    {
        // Validasi tipe file
        if (!in_array($file->getClientOriginalExtension(), ['jpeg', 'jpg', 'png', 'gif'])) {
            throw new \InvalidArgumentException('Tipe file tidak didukung. Gunakan JPEG, PNG, atau GIF');
        }

        // Generate nama file unik
        $filename = 'profile_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = 'profile_photos/' . $filename;
        $absolutePath = storage_path('app/public/' . $path);

        try {
            // Pastikan direktori ada
            $directory = dirname($absolutePath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Triple protection: coba berbagai metode
            if ($this->processWithInterventionImage($file, $absolutePath)) {
                Log::info('Image processed with Intervention Image', ['file' => $filename]);
                return $path;
            }
            
            if ($this->processWithNativeGD($file, $absolutePath)) {
                Log::info('Image processed with native GD', ['file' => $filename]);
                return $path;
            }
            
            // Last fallback: simpan file asli
            $fallbackPath = $file->store('profile_photos', 'public');
            Log::warning('Using original file without processing', ['file' => $filename]);
            return $fallbackPath;
            
        } catch (\Exception $e) {
            Log::error('Complete image processing failure', [
                'error' => $e->getMessage(),
                'file' => $filename,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Emergency fallback
            $emergencyPath = $file->store('profile_photos', 'public');
            return $emergencyPath;
        }
    }

    /**
     * Process image using Intervention Image with manual GD configuration
     */
    private function processWithInterventionImage(UploadedFile $file, string $absolutePath): bool
    {
        try {
            // Coba konfigurasi manual GD driver
            $manager = new \Intervention\Image\ImageManager(['driver' => 'gd']);
            $img = $manager->make($file->getRealPath());
            
            // Resize ke maksimal 800x800 dengan mempertahankan aspect ratio
            $img->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // Simpan langsung ke file
            $img->save($absolutePath, 85); // 85% quality
            
            return file_exists($absolutePath) && filesize($absolutePath) > 0;
            
        } catch (\Exception $e) {
            Log::warning('Intervention Image failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Process image using native GD functions as fallback
     */
    private function processWithNativeGD(UploadedFile $file, string $absolutePath): bool
    {
        try {
            if (!extension_loaded('gd')) {
                return false;
            }

            $sourceInfo = getimagesize($file->getRealPath());
            if (!$sourceInfo) {
                return false;
            }

            list($sourceWidth, $sourceHeight, $sourceType) = $sourceInfo;

            // Buat resource gambar berdasarkan tipe
            switch ($sourceType) {
                case IMAGETYPE_JPEG:
                    $sourceImage = imagecreatefromjpeg($file->getRealPath());
                    break;
                case IMAGETYPE_PNG:
                    $sourceImage = imagecreatefrompng($file->getRealPath());
                    break;
                case IMAGETYPE_GIF:
                    $sourceImage = imagecreatefromgif($file->getRealPath());
                    break;
                default:
                    return false;
            }

            if (!$sourceImage) {
                return false;
            }

            // Hitung dimensi baru (maksimal 800x800)
            $maxSize = 800;
            if ($sourceWidth <= $maxSize && $sourceHeight <= $maxSize) {
                // Jika sudah kecil, copy saja
                $newWidth = $sourceWidth;
                $newHeight = $sourceHeight;
            } else {
                // Resize dengan aspect ratio
                $ratio = min($maxSize / $sourceWidth, $maxSize / $sourceHeight);
                $newWidth = (int)($sourceWidth * $ratio);
                $newHeight = (int)($sourceHeight * $ratio);
            }

            // Buat gambar baru
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency for PNG and GIF
            if ($sourceType == IMAGETYPE_PNG || $sourceType == IMAGETYPE_GIF) {
                imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
            }

            // Resize
            imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);

            // Simpan berdasarkan tipe
            $success = false;
            switch ($sourceType) {
                case IMAGETYPE_JPEG:
                    $success = imagejpeg($newImage, $absolutePath, 85);
                    break;
                case IMAGETYPE_PNG:
                    $success = imagepng($newImage, $absolutePath, 8);
                    break;
                case IMAGETYPE_GIF:
                    $success = imagegif($newImage, $absolutePath);
                    break;
            }

            // Cleanup
            imagedestroy($sourceImage);
            imagedestroy($newImage);

            return $success && file_exists($absolutePath) && filesize($absolutePath) > 0;

        } catch (\Exception $e) {
            Log::warning('Native GD processing failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}