@extends(auth()->user()->isPenjual() ? 'layouts.penjual' : 'layouts.pembeli')

@section('title', __('Profile'))

@section('content')
    <div class="py-4">
        <div class="container">
            <h1 class="h3 mb-4">{{ __('Profile') }}</h1>



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
    </div>
@endsection
