@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-medium mb-4">Log in</h2>

    @if($errors->any())
        <div class="mb-4 text-red-600">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div class="mb-4">
            <label class="block text-sm mb-1">Email</label>
            <input name="email" value="{{ old('email') }}" class="w-full border px-3 py-2 rounded" />
        </div>

        <div class="mb-4">
            <label class="block text-sm mb-1">Password</label>
            <input name="password" type="password" class="w-full border px-3 py-2 rounded" />
        </div>

        <div class="flex items-center justify-between">
            <button class="px-4 py-2 bg-[#F53003] text-white rounded">Login</button>
            <a href="/register" class="text-sm text-[#706f6c]">Register</a>
        </div>
    </form>
</div>
@endsection
