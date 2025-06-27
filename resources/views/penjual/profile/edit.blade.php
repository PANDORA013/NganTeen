<x-app-layout :title="__('Profile')">
    <div class="py-4">
        <div class="container">
            <h1 class="h3 mb-4">{{ __('Profile') }}</h1>
        <!-- Debug Info -->
        @if(env('APP_DEBUG'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Debug Info: 
                            Role: {{ auth()->user()->role }}, 
                            isPenjual(): {{ auth()->user()->isPenjual() ? 'true' : 'false' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            @if(auth()->user()->isPenjual())
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('QRIS Payment Method') }}</h5>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.qris-upload-form')
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    {{ __('Anda login sebagai pembeli. Fitur QRIS hanya tersedia untuk penjual.') }}
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Update Password') }}</h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">{{ __('Delete Account') }}</h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
