@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <div class="flex items-center gap-6">
            <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                @if($user->profile_picture_url)
                    <img id="preview-img" src="{{ $user->profile_picture_url }}" alt="Profile" class="object-cover w-full h-full">
                @else
                    <img id="preview-img" src="{{ asset('images/avatar-placeholder.svg') }}" alt="Profile" class="object-cover w-full h-full">
                @endif
            </div>

            <div class="flex-1">
                <h1 class="text-xl font-semibold">Edit Profile</h1>
                <p class="text-sm text-gray-600">Update your account information and profile picture.</p>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-6">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Company Name</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}" class="mt-1 block w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 block w-full border rounded px-3 py-2">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Website</label>
                    <input type="text" name="website" value="{{ old('website', $user->website) }}" class="mt-1 block w-full border rounded px-3 py-2">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Bio</label>
                    <textarea name="bio" rows="4" class="mt-1 block w-full border rounded px-3 py-2">{{ old('bio', $user->bio) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea name="address" rows="2" class="mt-1 block w-full border rounded px-3 py-2">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Profile Picture</label>
                    <input id="profile_picture" type="file" name="profile_picture" accept="image/*" class="mt-1 block w-full">
                    <p class="text-xs text-gray-500 mt-2">Max 2 MB. JPG/PNG recommended. Preview shown above.</p>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Save Changes</button>
                <a href="{{ route('profile.show') }}" class="text-sm text-gray-600">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        const input = document.getElementById('profile_picture');
        const preview = document.getElementById('preview-img');

        if (input) {
            input.addEventListener('change', function(e){
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function(ev){
                    if (preview) preview.src = ev.target.result;
                };
                reader.readAsDataURL(file);
            });
        }
    });
</script>
@endsection
