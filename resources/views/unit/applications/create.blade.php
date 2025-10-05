@extends('layouts.app')

@section('title', 'Create Application')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">Create Application</h1>
        </div>
    </div>

    <form action="{{ route('unit.applications.store') }}" method="POST" class="mt-8">
        @csrf
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 transition-colors">
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                    <select name="type" id="type" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                        <option value="php">PHP</option>
                        <option value="nodejs">Node.js</option>
                        <option value="python">Python</option>
                        <option value="go">Go</option>
                        <option value="perl">Perl</option>
                        <option value="ruby">Ruby</option>
                        <option value="java">Java</option>
                    </select>
                </div>

                <div>
                    <label for="config" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Configuration (JSON)</label>
                    <textarea name="config" id="config" rows="10" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono">{
  "processes": 2,
  "root": "/var/www/html",
  "script": "index.php"
}</textarea>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Enter configuration as JSON</p>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"></textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('unit.applications.index') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200">Cancel</a>
                <button type="submit" class="rounded-md bg-blue-600 dark:bg-blue-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 dark:focus-visible:outline-blue-500 transition-colors">
                    Create
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
