@extends('layouts.app')

@section('title', 'Edit Route: ' . $name)

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">Edit Route: {{ $name }}</h1>
        </div>
    </div>

    <form action="{{ route('unit.routes.update', $name) }}" method="POST" class="mt-8">
        @csrf
        @method('PUT')
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 transition-colors">
            <div class="space-y-6">
                <div>
                    <label for="config" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Configuration (JSON)</label>
                    <textarea name="config" id="config" rows="10" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono">{{ json_encode($route->config, JSON_PRETTY_PRINT) }}</textarea>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Enter route configuration as JSON</p>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">{{ $route->description ?? '' }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('unit.routes.index') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200">Cancel</a>
                <button type="submit" class="rounded-md bg-blue-600 dark:bg-blue-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 dark:focus-visible:outline-blue-500 transition-colors">
                    Update
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

