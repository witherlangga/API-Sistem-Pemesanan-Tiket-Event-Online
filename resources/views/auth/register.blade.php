@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-medium mb-4">Register</h2>

    @if($errors->any())
        <div class="mb-4 text-red-600">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/register">
        @csrf

        <div class="mb-4">
            <label class="block text-sm mb-1">Name</label>
            <input name="name" value="{{ old('name') }}" class="w-full border px-3 py-2 rounded" />
        </div>

        <div class="mb-4">
            <label class="block text-sm mb-1">Email</label>
            <input name="email" value="{{ old('email') }}" class="w-full border px-3 py-2 rounded" />
        </div>

        <div class="mb-4 md:flex md:gap-4">
            <div class="md:flex-1">
                <label class="block text-sm mb-1">Password</label>
                <input name="password" type="password" class="w-full border px-3 py-2 rounded" />
            </div>
            <div class="md:flex-1">
                <label class="block text-sm mb-1">Confirm Password</label>
                <input name="password_confirmation" type="password" class="w-full border px-3 py-2 rounded" />
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm mb-1">Role</label>
            <select id="role" name="role" class="w-full border px-3 py-2 rounded">
                <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="organizer" {{ old('role') === 'organizer' ? 'selected' : '' }}>Organizer</option>
            </select>
        </div>

        <!-- Common non-sensitive profile fields (optional) -->
        <div class="mb-4">
            <label class="block text-sm mb-1">Phone (opsional)</label>
            <input name="phone" value="{{ old('phone') }}" class="w-full border px-3 py-2 rounded" />
        </div>

        <div class="mb-4">
            <label class="block text-sm mb-1">Website (opsional)</label>
            <input name="website" value="{{ old('website') }}" class="w-full border px-3 py-2 rounded" placeholder="https://example.com" />
        </div>

        <div class="mb-4">
            <label class="block text-sm mb-1">Alamat (opsional)</label>
            <input name="address" value="{{ old('address') }}" class="w-full border px-3 py-2 rounded" />
        </div>

        <div class="mb-4" id="company_field">
            <label class="block text-sm mb-1">Nama Perusahaan (hanya untuk organizer)</label>
            <input name="company_name" value="{{ old('company_name') }}" class="w-full border px-3 py-2 rounded" />
        </div>

        <div class="mb-4">
            <label class="block text-sm mb-1">Bio singkat (opsional)</label>
            <textarea name="bio" class="w-full border px-3 py-2 rounded" rows="3">{{ old('bio') }}</textarea>
        </div>

        <div class="flex items-center justify-between">
            <button class="px-4 py-2 bg-[#F53003] text-white rounded">Register</button>
            <a href="/login" class="text-sm text-[#706f6c]">Login</a>
        </div>
    </form>

    <script>
        (function(){
            const role = document.getElementById('role');
            const company = document.getElementById('company_field');
            function toggleCompany(){
                if(!role || !company) return;
                company.style.display = role.value === 'organizer' ? 'block' : 'none';
            }
            role?.addEventListener('change', toggleCompany);
            document.addEventListener('DOMContentLoaded', toggleCompany);
        })();
    </script>
</div>
@endsection
