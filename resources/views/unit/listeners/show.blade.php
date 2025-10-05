@extends('layouts.app')

@section('title', 'Listener: ' . $address)

@section('content')
<div class="px-4 sm:px-6 lg:px-8 pb-20">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">Listener: {{ $address }}</h1>
        </div>
    </div>

    <div class="mt-8 bg-white dark:bg-gray-800 shadow rounded-lg p-6 transition-colors">
        <pre class="bg-gray-50 dark:bg-gray-900 p-4 rounded text-sm overflow-x-auto text-gray-900 dark:text-gray-100"><code>{{ json_encode($listener, JSON_PRETTY_PRINT) }}</code></pre>
    </div>

    <div class="fixed bottom-4 right-4 flex gap-3">
        <a href="{{ route('unit.listeners.index') }}" class="rounded-md bg-gray-600 dark:bg-gray-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 dark:hover:bg-gray-600 transition-colors">
            Назад
        </a>
        <a href="{{ route('unit.listeners.edit', rawurlencode($address)) }}" class="rounded-md bg-blue-600 dark:bg-blue-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors">
            Редактировать
        </a>
    </div>
</div>
@endsection
