@php
/** @var \App\Models\User $user */
$user = auth()->user();
@endphp

<section class="mt-8">
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-medium text-gray-900 mb-4">
            {{ __('QRIS Payment Method') }}
        </h2>

        @if (session('status') === 'qris-uploaded')
            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                <span class="font-medium">{{ __('Success!') }}</span> {{ __('QRIS image has been uploaded successfully.') }}
            </div>
        @elseif(session('status') === 'qris-deleted')
            <div class="mb-4 p-4 text-sm text-blue-700 bg-blue-100 rounded-lg" role="alert">
                <span class="font-medium">{{ __('Success!') }}</span> {{ __('QRIS image has been removed.') }}
            </div>
        @elseif(session('status') === 'no-qris-found')
            <div class="mb-4 p-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg" role="alert">
                <span class="font-medium">{{ __('Info') }}</span> {{ __('No QRIS image found to delete.') }}
            </div>
        @endif

        @if ($errors->has('qris_image'))
            <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                <span class="font-medium">{{ __('Error!') }}</span> {{ $errors->first('qris_image') }}
            </div>
        @endif

        <div class="space-y-4">
            @if($user->qris_image)
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">{{ __('Current QRIS Image:') }}</p>
                    <div class="relative inline-block">
                        <img src="{{ asset('storage/' . $user->qris_image) }}" 
                             alt="QRIS Payment" 
                             class="h-48 w-auto border rounded-lg">
                        <form action="{{ route('profile.qris.delete') }}" method="POST" class="absolute -top-2 -right-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="p-1 bg-red-500 text-white rounded-full hover:bg-red-600"
                                    onclick="return confirm('{{ __('Are you sure you want to delete this QRIS image?') }}')"
                                    title="{{ __('Delete QRIS Image') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <form action="{{ route('profile.qris.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div>
                    <label for="qris_image" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $user->qris_image ? __('Update QRIS Image') : __('Upload QRIS Image') }}
                    </label>
                    <div class="mt-1 flex items-center">
                        <input type="file" 
                               id="qris_image" 
                               name="qris_image" 
                               accept="image/jpeg,image/png,image/jpg"
                               class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-md file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100"
                               required>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        {{ __('Upload a clear image of your QRIS code. Max size: 2MB. Allowed formats: JPG, PNG.') }}
                    </p>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ $user->qris_image ? __('Update QRIS') : __('Upload QRIS') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
