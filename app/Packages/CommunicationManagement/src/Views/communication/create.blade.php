@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Create NotificationSetting</h1>
    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{ route('communication.store') }">
            @csrf
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
        </form>
    </div>
</div>
@endsection
