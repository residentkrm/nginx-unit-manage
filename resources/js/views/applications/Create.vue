<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1
                    class="text-3xl font-semibold text-gray-900 dark:text-white"
                >
                    Create Application
                </h1>
            </div>
        </div>
        <form class="mt-8" @submit.prevent="submit">
            <div
                class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 transition-colors"
            >
                <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button
                            type="button"
                            :class="
                                activeTab === 'form'
                                    ? 'border-blue-500 text-blue-600 dark:text-blue-400'
                                    : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'
                            "
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                            @click="activeTab = 'form'"
                        >
                            Форма
                        </button>
                        <button
                            type="button"
                            :class="
                                activeTab === 'json'
                                    ? 'border-blue-500 text-blue-600 dark:text-blue-400'
                                    : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'
                            "
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                            @click="
                                activeTab = 'json';
                                syncToJson();
                            "
                        >
                            JSON
                        </button>
                    </nav>
                </div>
                <div v-show="activeTab === 'form'" class="space-y-6">
                    <div>
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Name</label
                        >
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                        />
                    </div>
                    <div>
                        <label
                            for="type"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Type</label
                        >
                        <input
                            id="type"
                            v-model="form.type"
                            type="text"
                            required
                            placeholder="php"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                        />
                        <p
                            class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                        >
                            Application type: external, java, perl, php, python,
                            ruby, wasm. You can specify version: "php 7",
                            "python 3.4", etc.
                        </p>
                    </div>
                    <div
                        v-if="baseType === 'php'"
                        class="border-t border-gray-200 dark:border-gray-700 pt-6"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        >
                            PHP Configuration
                        </h3>
                        <div>
                            <label
                                for="root"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Root <span class="text-red-500">*</span></label
                            >
                            <input
                                id="root"
                                v-model="form.root"
                                type="text"
                                required
                                placeholder="/var/www/html"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                            />
                        </div>
                        <div class="mt-4">
                            <label
                                for="script"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Script (optional)</label
                            >
                            <input
                                id="script"
                                v-model="form.script"
                                type="text"
                                placeholder="index.php"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                            />
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                If set, all requests are handled by this script.
                                Otherwise, requests are served by URI paths.
                            </p>
                        </div>
                        <div class="mt-4">
                            <label
                                for="index"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Index (optional)</label
                            >
                            <input
                                id="index"
                                v-model="form.index"
                                type="text"
                                placeholder="index.php"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                            />
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Filename added to URI paths that point to
                                directories (default: index.php)
                            </p>
                        </div>
                        <div class="mt-4">
                            <label
                                for="phpOptions"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >PHP Options (JSON, optional)</label
                            >
                            <textarea
                                id="phpOptions"
                                v-model="form.phpOptions"
                                rows="6"
                                placeholder='{"file": "/etc/php.ini", "admin": {"memory_limit": "256M"}, "user": {"display_errors": "0"}}'
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"
                            ></textarea>
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                PHP configuration: file (php.ini path), admin
                                (PHP_INI_SYSTEM), user (PHP_INI_USER)
                            </p>
                        </div>
                        <div class="mt-4">
                            <label
                                for="targets"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Targets (JSON, optional)</label
                            >
                            <textarea
                                id="targets"
                                v-model="form.targets"
                                rows="6"
                                placeholder='{"front": {"script": "front.php", "root": "/www/app/front/"}, "back": {"script": "back.php", "root": "/www/app/back/"}}'
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"
                            ></textarea>
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Up to 254 entry points. Cannot be used with
                                root/script/index at app level.
                            </p>
                        </div>
                    </div>
                    <div
                        v-if="baseType === 'external'"
                        class="border-t border-gray-200 dark:border-gray-700 pt-6"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        >
                            Node.js Configuration
                        </h3>
                        <div>
                            <label
                                for="executable"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Executable
                                <span class="text-red-500">*</span></label
                            >
                            <input
                                id="executable"
                                v-model="form.executable"
                                type="text"
                                required
                                placeholder="app.js"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                            />
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Pathname of the app, absolute or relative to
                                working_directory. Make sure to chmod +x the
                                file.
                            </p>
                        </div>
                        <div class="mt-4">
                            <label
                                for="arguments"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Arguments (JSON array, optional)</label
                            >
                            <textarea
                                id="arguments"
                                v-model="form.arguments"
                                rows="3"
                                placeholder='["--tmp-files", "/tmp/node-cache"]'
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"
                            ></textarea>
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Command-line arguments to be passed to the app
                            </p>
                        </div>
                    </div>
                    <div
                        v-if="baseType === 'perl'"
                        class="border-t border-gray-200 dark:border-gray-700 pt-6"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        >
                            Perl Configuration
                        </h3>
                        <div>
                            <label
                                for="script"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Script (PSGI)
                                <span class="text-red-500">*</span></label
                            >
                            <input
                                id="script"
                                v-model="form.script"
                                type="text"
                                required
                                placeholder="/www/bugtracker/app.psgi"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                            />
                        </div>
                        <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label
                                    for="threads"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Threads (optional)</label
                                >
                                <input
                                    id="threads"
                                    v-model.number="form.threads"
                                    type="number"
                                    min="1"
                                    placeholder="1"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                                />
                                <p
                                    class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Number of worker threads per app process
                                    (default: 1)
                                </p>
                            </div>
                            <div>
                                <label
                                    for="threadStackSize"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Thread Stack Size (bytes, optional)</label
                                >
                                <input
                                    id="threadStackSize"
                                    v-model.number="form.threadStackSize"
                                    type="number"
                                    min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                                />
                                <p
                                    class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Stack size of a worker thread (multiple of
                                    memory page size)
                                </p>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="
                            baseType !== 'php' &&
                            baseType !== 'external' &&
                            baseType !== 'perl'
                        "
                        class="border-t border-gray-200 dark:border-gray-700 pt-6"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        >
                            Paths & Scripts
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label
                                    for="root"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Root (optional)</label
                                >
                                <input
                                    id="root"
                                    v-model="form.root"
                                    type="text"
                                    placeholder="/var/www/html"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                                />
                            </div>
                            <div>
                                <label
                                    for="workingDirectory"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Working Directory (optional)</label
                                >
                                <input
                                    id="workingDirectory"
                                    v-model="form.workingDirectory"
                                    type="text"
                                    placeholder="/var/www/html"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                                />
                            </div>
                        </div>
                        <div class="mt-4">
                            <label
                                for="script"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Script (optional)</label
                            >
                            <input
                                id="script"
                                v-model="form.script"
                                type="text"
                                placeholder="index.php"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                            />
                        </div>
                    </div>
                    <div
                        v-if="baseType === 'external' || baseType === 'perl'"
                        class="border-t border-gray-200 dark:border-gray-700 pt-6"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        >
                            Paths
                        </h3>
                        <div>
                            <label
                                for="workingDirectory"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Working Directory (optional)</label
                            >
                            <input
                                id="workingDirectory"
                                v-model="form.workingDirectory"
                                type="text"
                                placeholder="/var/www/html"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                            />
                        </div>
                    </div>
                    <div
                        class="border-t border-gray-200 dark:border-gray-700 pt-6"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        >
                            Process Management
                        </h3>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >Processes</label
                            >
                            <div class="flex items-center gap-4">
                                <label class="flex items-center">
                                    <input
                                        v-model="form.processesMode"
                                        type="radio"
                                        value="static"
                                        class="mr-2"
                                    />
                                    <span
                                        class="text-sm text-gray-700 dark:text-gray-300"
                                        >Static</span
                                    >
                                </label>
                                <label class="flex items-center">
                                    <input
                                        v-model="form.processesMode"
                                        type="radio"
                                        value="dynamic"
                                        class="mr-2"
                                    />
                                    <span
                                        class="text-sm text-gray-700 dark:text-gray-300"
                                        >Dynamic</span
                                    >
                                </label>
                            </div>
                        </div>
                        <div
                            v-if="form.processesMode === 'static'"
                            class="mt-4"
                        >
                            <label
                                for="processes"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Number of Processes</label
                            >
                            <input
                                id="processes"
                                v-model.number="form.processes"
                                type="number"
                                min="1"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                            />
                        </div>
                        <div
                            v-else
                            class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3"
                        >
                            <div>
                                <label
                                    for="processesMax"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Max</label
                                >
                                <input
                                    id="processesMax"
                                    v-model.number="form.processesMax"
                                    type="number"
                                    min="1"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                                />
                            </div>
                            <div>
                                <label
                                    for="processesSpare"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Spare</label
                                >
                                <input
                                    id="processesSpare"
                                    v-model.number="form.processesSpare"
                                    type="number"
                                    min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                                />
                            </div>
                            <div>
                                <label
                                    for="processesIdleTimeout"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Idle Timeout (seconds)</label
                                >
                                <input
                                    id="processesIdleTimeout"
                                    v-model.number="form.processesIdleTimeout"
                                    type="number"
                                    min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                                />
                            </div>
                        </div>
                    </div>
                    <div
                        class="border-t border-gray-200 dark:border-gray-700 pt-6"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        >
                            User & Permissions
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label
                                    for="user"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >User (optional)</label
                                >
                                <input
                                    id="user"
                                    v-model="form.user"
                                    type="text"
                                    placeholder="www-data"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                                />
                            </div>
                            <div>
                                <label
                                    for="group"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Group (optional)</label
                                >
                                <input
                                    id="group"
                                    v-model="form.group"
                                    type="text"
                                    placeholder="www-data"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                                />
                            </div>
                        </div>
                    </div>
                    <div
                        class="border-t border-gray-200 dark:border-gray-700 pt-6"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        >
                            Limits
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label
                                    for="limitsTimeout"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Timeout (seconds, optional)</label
                                >
                                <input
                                    id="limitsTimeout"
                                    v-model.number="form.limitsTimeout"
                                    type="number"
                                    min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                                />
                            </div>
                            <div>
                                <label
                                    for="limitsRequests"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Requests (optional)</label
                                >
                                <input
                                    id="limitsRequests"
                                    v-model.number="form.limitsRequests"
                                    type="number"
                                    min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                                />
                            </div>
                        </div>
                    </div>
                    <div
                        class="border-t border-gray-200 dark:border-gray-700 pt-6"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        >
                            Output Redirection
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label
                                    for="stdout"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Stdout (optional)</label
                                >
                                <input
                                    id="stdout"
                                    v-model="form.stdout"
                                    type="text"
                                    placeholder="/var/log/app/stdout.log"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                                />
                            </div>
                            <div>
                                <label
                                    for="stderr"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Stderr (optional)</label
                                >
                                <input
                                    id="stderr"
                                    v-model="form.stderr"
                                    type="text"
                                    placeholder="/var/log/app/stderr.log"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                                />
                            </div>
                        </div>
                    </div>
                    <div
                        class="border-t border-gray-200 dark:border-gray-700 pt-6"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        >
                            Environment
                        </h3>
                        <div>
                            <label
                                for="environment"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Environment Variables (JSON, optional)</label
                            >
                            <textarea
                                id="environment"
                                v-model="form.environment"
                                rows="4"
                                placeholder='{"APP_ENV": "production"}'
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"
                            ></textarea>
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Enter environment variables as JSON object
                            </p>
                        </div>
                    </div>
                    <div>
                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Description</label
                        >
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                        ></textarea>
                    </div>
                </div>
                <div v-show="activeTab === 'json'" class="space-y-6">
                    <div>
                        <label
                            for="nameJson"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Name</label
                        >
                        <input
                            id="nameJson"
                            v-model="form.name"
                            type="text"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                        />
                    </div>
                    <div>
                        <label
                            for="typeJson"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Type</label
                        >
                        <input
                            id="typeJson"
                            v-model="form.type"
                            type="text"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                        />
                    </div>
                    <div>
                        <label
                            for="configJson"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Configuration (JSON)</label
                        >
                        <textarea
                            id="configJson"
                            v-model="jsonConfig"
                            rows="15"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"
                        ></textarea>
                        <p
                            class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                        >
                            Enter configuration as JSON
                        </p>
                    </div>
                    <div>
                        <label
                            for="descriptionJson"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Description</label
                        >
                        <textarea
                            id="descriptionJson"
                            v-model="form.description"
                            rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                        ></textarea>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <router-link
                        to="/unit/applications"
                        class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200"
                        >Cancel</router-link
                    >
                    <button
                        type="submit"
                        :disabled="loading"
                        class="rounded-md bg-blue-600 dark:bg-blue-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 dark:focus-visible:outline-blue-500 transition-colors disabled:opacity-50"
                    >
                        {{ loading ? "Creating..." : "Create" }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
<script setup>
import { ref, computed } from "vue";
import { useRouter } from "vue-router";
import { useApi } from "../../composables/useApi";
import { useAlert } from "../../composables/useAlert";
const { post } = useApi();
const { showAlert } = useAlert();
const router = useRouter();
const loading = ref(false);
const activeTab = ref("form");
const form = ref({
    name: "",
    type: "php",
    processesMode: "static",
    processes: 2,
    processesMax: 10,
    processesSpare: 2,
    processesIdleTimeout: 60,
    root: "",
    workingDirectory: "",
    script: "",
    index: "",
    executable: "",
    arguments: "",
    threads: null,
    threadStackSize: null,
    phpOptions: "",
    targets: "",
    user: "",
    group: "",
    limitsTimeout: null,
    limitsRequests: null,
    stdout: "",
    stderr: "",
    environment: "",
    description: "",
});
const baseType = computed(() => {
    const type = form.value.type.toLowerCase().trim();
    if (type.startsWith("php")) return "php";
    if (type.startsWith("external")) return "external";
    if (type.startsWith("perl")) return "perl";
    if (type.startsWith("python")) return "python";
    if (type.startsWith("ruby")) return "ruby";
    if (type.startsWith("java")) return "java";
    if (type.startsWith("go")) return "go";
    if (type.startsWith("wasm")) return "wasm";
    return type.split(" ")[0]; // fallback to first word
});
const jsonConfig = ref(
    JSON.stringify(
        {
            processes: 2,
            root: "/var/www/html",
            script: "index.php",
        },
        null,
        2,
    ),
);
const syncToJson = () => {
    const config = {};
    // Processes
    if (form.value.processesMode === "static") {
        if (form.value.processes) {
            config.processes = form.value.processes;
        }
    } else {
        const processesObj = {};
        if (form.value.processesMax) processesObj.max = form.value.processesMax;
        if (form.value.processesSpare)
            processesObj.spare = form.value.processesSpare;
        if (form.value.processesIdleTimeout)
            processesObj.idle_timeout = form.value.processesIdleTimeout;
        if (Object.keys(processesObj).length > 0) {
            config.processes = processesObj;
        }
    }
    // Type-specific options
    if (baseType.value === "php") {
        if (form.value.root) {
            config.root = form.value.root;
        }
        if (form.value.script) {
            config.script = form.value.script;
        }
        if (form.value.index) {
            config.index = form.value.index;
        }
        if (form.value.phpOptions) {
            try {
                config.options = JSON.parse(form.value.phpOptions);
            } catch (_e) {
                // Keep as is if invalid
            }
        }
        if (form.value.targets) {
            try {
                config.targets = JSON.parse(form.value.targets);
            } catch (_e) {
                // Keep as is if invalid
            }
        }
    } else if (baseType.value === "external") {
        if (form.value.executable) {
            config.executable = form.value.executable;
        }
        if (form.value.arguments) {
            try {
                config.arguments = JSON.parse(form.value.arguments);
            } catch (_e) {
                // Keep as is if invalid
            }
        }
    } else if (baseType.value === "perl") {
        if (form.value.script) {
            config.script = form.value.script;
        }
        if (form.value.threads) {
            config.threads = form.value.threads;
        }
        if (form.value.threadStackSize) {
            config.thread_stack_size = form.value.threadStackSize;
        }
    } else {
        // Common paths for other types
        if (form.value.root) {
            config.root = form.value.root;
        }
        if (form.value.script) {
            config.script = form.value.script;
        }
    }
    // Common paths
    if (form.value.workingDirectory) {
        config.working_directory = form.value.workingDirectory;
    }
    // User & Group
    if (form.value.user) {
        config.user = form.value.user;
    }
    if (form.value.group) {
        config.group = form.value.group;
    }
    // Limits
    const limits = {};
    if (form.value.limitsTimeout !== null && form.value.limitsTimeout !== "") {
        limits.timeout = form.value.limitsTimeout;
    }
    if (
        form.value.limitsRequests !== null &&
        form.value.limitsRequests !== ""
    ) {
        limits.requests = form.value.limitsRequests;
    }
    if (Object.keys(limits).length > 0) {
        config.limits = limits;
    }
    // Output
    if (form.value.stdout) {
        config.stdout = form.value.stdout;
    }
    if (form.value.stderr) {
        config.stderr = form.value.stderr;
    }
    // Environment
    if (form.value.environment) {
        try {
            config.environment = JSON.parse(form.value.environment);
        } catch (_e) {
            // Keep as is if invalid
        }
    }
    jsonConfig.value = JSON.stringify(config, null, 2);
};
const submit = async () => {
    loading.value = true;
    try {
        let config;
        if (activeTab.value === "json") {
            try {
                config = JSON.parse(jsonConfig.value);
            } catch (_e) {
                showAlert("Invalid JSON format", "error");
                loading.value = false;
                return;
            }
        } else {
            config = {};
            // Processes
            if (form.value.processesMode === "static") {
                if (form.value.processes) {
                    config.processes = form.value.processes;
                }
            } else {
                const processesObj = {};
                if (form.value.processesMax)
                    processesObj.max = form.value.processesMax;
                if (form.value.processesSpare)
                    processesObj.spare = form.value.processesSpare;
                if (form.value.processesIdleTimeout)
                    processesObj.idle_timeout = form.value.processesIdleTimeout;
                if (Object.keys(processesObj).length > 0) {
                    config.processes = processesObj;
                }
            }
            // Type-specific options
            if (baseType.value === "php") {
                if (!form.value.root) {
                    showAlert("Root is required for PHP applications", "error");
                    loading.value = false;
                    return;
                }
                config.root = form.value.root;
                if (form.value.script) {
                    config.script = form.value.script;
                }
                if (form.value.index) {
                    config.index = form.value.index;
                }
                if (form.value.phpOptions) {
                    try {
                        config.options = JSON.parse(form.value.phpOptions);
                    } catch (_e) {
                        showAlert("Invalid PHP options JSON format", "error");
                        loading.value = false;
                        return;
                    }
                }
                if (form.value.targets) {
                    try {
                        config.targets = JSON.parse(form.value.targets);
                    } catch (_e) {
                        showAlert("Invalid targets JSON format", "error");
                        loading.value = false;
                        return;
                    }
                }
            } else if (baseType.value === "external") {
                if (!form.value.executable) {
                    showAlert(
                        "Executable is required for external applications",
                        "error",
                    );
                    loading.value = false;
                    return;
                }
                config.executable = form.value.executable;
                if (form.value.arguments) {
                    try {
                        config.arguments = JSON.parse(form.value.arguments);
                    } catch (_e) {
                        showAlert("Invalid arguments JSON format", "error");
                        loading.value = false;
                        return;
                    }
                }
            } else if (baseType.value === "perl") {
                if (!form.value.script) {
                    showAlert(
                        "Script is required for Perl applications",
                        "error",
                    );
                    loading.value = false;
                    return;
                }
                config.script = form.value.script;
                if (form.value.threads) {
                    config.threads = form.value.threads;
                }
                if (form.value.threadStackSize) {
                    config.thread_stack_size = form.value.threadStackSize;
                }
            } else {
                // Common paths for other types
                if (form.value.root) {
                    config.root = form.value.root;
                }
                if (form.value.script) {
                    config.script = form.value.script;
                }
            }
            // Common paths
            if (form.value.workingDirectory) {
                config.working_directory = form.value.workingDirectory;
            }
            // User & Group
            if (form.value.user) {
                config.user = form.value.user;
            }
            if (form.value.group) {
                config.group = form.value.group;
            }
            // Limits
            const limits = {};
            if (
                form.value.limitsTimeout !== null &&
                form.value.limitsTimeout !== ""
            ) {
                limits.timeout = form.value.limitsTimeout;
            }
            if (
                form.value.limitsRequests !== null &&
                form.value.limitsRequests !== ""
            ) {
                limits.requests = form.value.limitsRequests;
            }
            if (Object.keys(limits).length > 0) {
                config.limits = limits;
            }
            // Output
            if (form.value.stdout) {
                config.stdout = form.value.stdout;
            }
            if (form.value.stderr) {
                config.stderr = form.value.stderr;
            }
            // Environment
            if (form.value.environment) {
                try {
                    config.environment = JSON.parse(form.value.environment);
                } catch (_e) {
                    showAlert(
                        "Invalid environment variables JSON format",
                        "error",
                    );
                    loading.value = false;
                    return;
                }
            }
        }
        const result = await post("/unit/applications", {
            name: form.value.name,
            type: form.value.type,
            config: JSON.stringify(config),
            description: form.value.description,
        });
        if (result.success) {
            showAlert("Application created successfully", "success");
            router.push("/unit/applications");
        } else {
            showAlert(result.error || "Failed to create application", "error");
        }
    } catch (_error) {
        showAlert("Failed to create application", "error");
    } finally {
        loading.value = false;
    }
};
</script>
