@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-2">Customer Dashboard</h1>
    <p class="mb-4">Welcome, {{ $user->name }} (Customer)</p>

    <div class="grid grid-cols-1 gap-4">
        <div class="p-4 border rounded">Your tickets: (placeholder)</div>
        <div class="p-4 border rounded">Recommendations: (placeholder)</div>
    </div>
</div>
@endsection
