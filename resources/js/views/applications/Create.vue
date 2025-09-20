<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">Create Application</h1>
            </div>
        </div>

        <form @submit.prevent="submit" class="mt-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 transition-colors">
                <!-- Tabs -->
                <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button
                            type="button"
                            @click="activeTab = 'form'"
                            :class="activeTab === 'form' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        >
                            Форма
                        </button>
                        <button
                            type="button"
                            @click="activeTab = 'json'; syncToJson()"
                            :class="activeTab === 'json' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        >
                            JSON
                        </button>
                    </nav>
                </div>

                <!-- Form Tab -->
                <div v-show="activeTab === 'form'" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input v-model="form.name" type="text" id="name" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                        <input v-model="form.type" type="text" id="type" required placeholder="php" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Application type: external, java, perl, php, python, ruby, wasm. You can specify version: "php 7", "python 3.4", etc.</p>
                    </div>

                    <!-- PHP Specific Options -->
                    <div v-if="baseType === 'php'" class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">PHP Configuration</h3>
                        
                        <div>
                            <label for="root" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Root <span class="text-red-500">*</span></label>
                            <input v-model="form.root" type="text" id="root" required placeholder="/var/www/html" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                        </div>

                        <div class="mt-4">
                            <label for="script" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Script (optional)</label>
                            <input v-model="form.script" type="text" id="script" placeholder="index.php" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">If set, all requests are handled by this script. Otherwise, requests are served by URI paths.</p>
                        </div>

                        <div class="mt-4">
                            <label for="index" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Index (optional)</label>
                            <input v-model="form.index" type="text" id="index" placeholder="index.php" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Filename added to URI paths that point to directories (default: index.php)</p>
                        </div>

                        <div class="mt-4">
                            <label for="phpOptions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">PHP Options (JSON, optional)</label>
                            <textarea v-model="form.phpOptions" id="phpOptions" rows="6" placeholder='{"file": "/etc/php.ini", "admin": {"memory_limit": "256M"}, "user": {"display_errors": "0"}}' class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"></textarea>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">PHP configuration: file (php.ini path), admin (PHP_INI_SYSTEM), user (PHP_INI_USER)</p>
                        </div>

                        <div class="mt-4">
                            <label for="targets" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Targets (JSON, optional)</label>
                            <textarea v-model="form.targets" id="targets" rows="6" placeholder='{"front": {"script": "front.php", "root": "/www/app/front/"}, "back": {"script": "back.php", "root": "/www/app/back/"}}' class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"></textarea>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Up to 254 entry points. Cannot be used with root/script/index at app level.</p>
                        </div>
                    </div>

                    <!-- Node.js (external) Specific Options -->
                    <div v-if="baseType === 'external'" class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Node.js Configuration</h3>
                        
                        <div>
                            <label for="executable" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Executable <span class="text-red-500">*</span></label>
                            <input v-model="form.executable" type="text" id="executable" required placeholder="app.js" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Pathname of the app, absolute or relative to working_directory. Make sure to chmod +x the file.</p>
                        </div>

                        <div class="mt-4">
                            <label for="arguments" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Arguments (JSON array, optional)</label>
                            <textarea v-model="form.arguments" id="arguments" rows="3" placeholder='["--tmp-files", "/tmp/node-cache"]' class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"></textarea>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Command-line arguments to be passed to the app</p>
                        </div>
                    </div>

                    <!-- Perl Specific Options -->
                    <div v-if="baseType === 'perl'" class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Perl Configuration</h3>
                        
                        <div>
                            <label for="script" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Script (PSGI) <span class="text-red-500">*</span></label>
                            <input v-model="form.script" type="text" id="script" required placeholder="/www/bugtracker/app.psgi" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="threads" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Threads (optional)</label>
                                <input v-model.number="form.threads" type="number" id="threads" min="1" placeholder="1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Number of worker threads per app process (default: 1)</p>
                            </div>

                            <div>
                                <label for="threadStackSize" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Thread Stack Size (bytes, optional)</label>
                                <input v-model.number="form.threadStackSize" type="number" id="threadStackSize" min="0" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Stack size of a worker thread (multiple of memory page size)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Common Paths & Scripts (for non-PHP, non-external, non-perl) -->
                    <div v-if="baseType !== 'php' && baseType !== 'external' && baseType !== 'perl'" class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Paths & Scripts</h3>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="root" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Root (optional)</label>
                                <input v-model="form.root" type="text" id="root" placeholder="/var/www/html" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            </div>

                            <div>
                                <label for="workingDirectory" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Working Directory (optional)</label>
                                <input v-model="form.workingDirectory" type="text" id="workingDirectory" placeholder="/var/www/html" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="script" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Script (optional)</label>
                            <input v-model="form.script" type="text" id="script" placeholder="index.php" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                        </div>
                    </div>

                    <!-- Working Directory for external and perl -->
                    <div v-if="baseType === 'external' || baseType === 'perl'" class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Paths</h3>
                        
                        <div>
                            <label for="workingDirectory" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Working Directory (optional)</label>
                            <input v-model="form.workingDirectory" type="text" id="workingDirectory" placeholder="/var/www/html" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Process Management</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Processes</label>
                            <div class="flex items-center gap-4">
                                <label class="flex items-center">
                                    <input type="radio" v-model="form.processesMode" value="static" class="mr-2">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Static</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" v-model="form.processesMode" value="dynamic" class="mr-2">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Dynamic</span>
                                </label>
                            </div>
                        </div>

                        <div v-if="form.processesMode === 'static'" class="mt-4">
                            <label for="processes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Number of Processes</label>
                            <input v-model.number="form.processes" type="number" id="processes" min="1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                        </div>

                        <div v-else class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div>
                                <label for="processesMax" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max</label>
                                <input v-model.number="form.processesMax" type="number" id="processesMax" min="1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            </div>
                            <div>
                                <label for="processesSpare" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Spare</label>
                                <input v-model.number="form.processesSpare" type="number" id="processesSpare" min="0" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            </div>
                            <div>
                                <label for="processesIdleTimeout" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Idle Timeout (seconds)</label>
                                <input v-model.number="form.processesIdleTimeout" type="number" id="processesIdleTimeout" min="0" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">User & Permissions</h3>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="user" class="block text-sm font-medium text-gray-700 dark:text-gray-300">User (optional)</label>
                                <input v-model="form.user" type="text" id="user" placeholder="www-data" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            </div>

                            <div>
                                <label for="group" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Group (optional)</label>
                                <input v-model="form.group" type="text" id="group" placeholder="www-data" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Limits</h3>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="limitsTimeout" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Timeout (seconds, optional)</label>
                                <input v-model.number="form.limitsTimeout" type="number" id="limitsTimeout" min="0" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            </div>

                            <div>
                                <label for="limitsRequests" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Requests (optional)</label>
                                <input v-model.number="form.limitsRequests" type="number" id="limitsRequests" min="0" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Output Redirection</h3>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="stdout" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stdout (optional)</label>
                                <input v-model="form.stdout" type="text" id="stdout" placeholder="/var/log/app/stdout.log" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            </div>

                            <div>
                                <label for="stderr" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stderr (optional)</label>
                                <input v-model="form.stderr" type="text" id="stderr" placeholder="/var/log/app/stderr.log" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Environment</h3>
                        
                        <div>
                            <label for="environment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Environment Variables (JSON, optional)</label>
                            <textarea v-model="form.environment" id="environment" rows="4" placeholder='{"APP_ENV": "production"}' class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"></textarea>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Enter environment variables as JSON object</p>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea v-model="form.description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"></textarea>
                    </div>
                </div>

                <!-- JSON Tab -->
                <div v-show="activeTab === 'json'" class="space-y-6">
                    <div>
                        <label for="nameJson" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input v-model="form.name" type="text" id="nameJson" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                    </div>

                    <div>
                        <label for="typeJson" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                        <input v-model="form.type" type="text" id="typeJson" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                    </div>

                    <div>
                        <label for="configJson" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Configuration (JSON)</label>
                        <textarea v-model="jsonConfig" id="configJson" rows="15" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"></textarea>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Enter configuration as JSON</p>
                    </div>

                    <div>
                        <label for="descriptionJson" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea v-model="form.description" id="descriptionJson" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <router-link to="/unit/applications" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200">Cancel</router-link>
                    <button type="submit" :disabled="loading" class="rounded-md bg-blue-600 dark:bg-blue-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 dark:focus-visible:outline-blue-500 transition-colors disabled:opacity-50">
                        {{ loading ? 'Creating...' : 'Create' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useApi } from '../../composables/useApi';
import { useAlert } from '../../composables/useAlert';

const { post } = useApi();
const { showAlert } = useAlert();
const router = useRouter();
const loading = ref(false);
const activeTab = ref('form');

const form = ref({
    name: '',
    type: 'php',
    processesMode: 'static',
    processes: 2,
    processesMax: 10,
    processesSpare: 2,
    processesIdleTimeout: 60,
    root: '',
    workingDirectory: '',
    script: '',
    index: '',
    executable: '',
    arguments: '',
    threads: null,
    threadStackSize: null,
    phpOptions: '',
    targets: '',
    user: '',
    group: '',
    limitsTimeout: null,
    limitsRequests: null,
    stdout: '',
    stderr: '',
    environment: '',
    description: ''
});

const baseType = computed(() => {
    const type = form.value.type.toLowerCase().trim();
    if (type.startsWith('php')) return 'php';
    if (type.startsWith('external')) return 'external';
    if (type.startsWith('perl')) return 'perl';
    if (type.startsWith('python')) return 'python';
    if (type.startsWith('ruby')) return 'ruby';
    if (type.startsWith('java')) return 'java';
    if (type.startsWith('go')) return 'go';
    if (type.startsWith('wasm')) return 'wasm';
    return type.split(' ')[0]; // fallback to first word
});

const jsonConfig = ref(JSON.stringify({
    processes: 2,
    root: '/var/www/html',
    script: 'index.php'
}, null, 2));

const syncToJson = () => {
    const config = {};

    // Processes
    if (form.value.processesMode === 'static') {
        if (form.value.processes) {
            config.processes = form.value.processes;
        }
    } else {
        const processesObj = {};
        if (form.value.processesMax) processesObj.max = form.value.processesMax;
        if (form.value.processesSpare) processesObj.spare = form.value.processesSpare;
        if (form.value.processesIdleTimeout) processesObj.idle_timeout = form.value.processesIdleTimeout;
        if (Object.keys(processesObj).length > 0) {
            config.processes = processesObj;
        }
    }

    // Type-specific options
    if (baseType.value === 'php') {
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
            } catch (e) {
                // Keep as is if invalid
            }
        }
        if (form.value.targets) {
            try {
                config.targets = JSON.parse(form.value.targets);
            } catch (e) {
                // Keep as is if invalid
            }
        }
    } else if (baseType.value === 'external') {
        if (form.value.executable) {
            config.executable = form.value.executable;
        }
        if (form.value.arguments) {
            try {
                config.arguments = JSON.parse(form.value.arguments);
            } catch (e) {
                // Keep as is if invalid
            }
        }
    } else if (baseType.value === 'perl') {
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
    if (form.value.limitsTimeout !== null && form.value.limitsTimeout !== '') {
        limits.timeout = form.value.limitsTimeout;
    }
    if (form.value.limitsRequests !== null && form.value.limitsRequests !== '') {
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
        } catch (e) {
            // Keep as is if invalid
        }
    }

    jsonConfig.value = JSON.stringify(config, null, 2);
};

const submit = async () => {
    loading.value = true;
    try {
        let config;
        
        if (activeTab.value === 'json') {
            try {
                config = JSON.parse(jsonConfig.value);
            } catch (e) {
                showAlert('Invalid JSON format', 'error');
                loading.value = false;
                return;
            }
        } else {
            config = {};
            // Processes
            if (form.value.processesMode === 'static') {
                if (form.value.processes) {
                    config.processes = form.value.processes;
                }
            } else {
                const processesObj = {};
                if (form.value.processesMax) processesObj.max = form.value.processesMax;
                if (form.value.processesSpare) processesObj.spare = form.value.processesSpare;
                if (form.value.processesIdleTimeout) processesObj.idle_timeout = form.value.processesIdleTimeout;
                if (Object.keys(processesObj).length > 0) {
                    config.processes = processesObj;
                }
            }

            // Type-specific options
            if (baseType.value === 'php') {
                if (!form.value.root) {
                    showAlert('Root is required for PHP applications', 'error');
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
                    } catch (e) {
                        showAlert('Invalid PHP options JSON format', 'error');
                        loading.value = false;
                        return;
                    }
                }
                if (form.value.targets) {
                    try {
                        config.targets = JSON.parse(form.value.targets);
                    } catch (e) {
                        showAlert('Invalid targets JSON format', 'error');
                        loading.value = false;
                        return;
                    }
                }
            } else if (baseType.value === 'external') {
                if (!form.value.executable) {
                    showAlert('Executable is required for external applications', 'error');
                    loading.value = false;
                    return;
                }
                config.executable = form.value.executable;
                if (form.value.arguments) {
                    try {
                        config.arguments = JSON.parse(form.value.arguments);
                    } catch (e) {
                        showAlert('Invalid arguments JSON format', 'error');
                        loading.value = false;
                        return;
                    }
                }
            } else if (baseType.value === 'perl') {
                if (!form.value.script) {
                    showAlert('Script is required for Perl applications', 'error');
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
            if (form.value.limitsTimeout !== null && form.value.limitsTimeout !== '') {
                limits.timeout = form.value.limitsTimeout;
            }
            if (form.value.limitsRequests !== null && form.value.limitsRequests !== '') {
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
                } catch (e) {
                    showAlert('Invalid environment variables JSON format', 'error');
                    loading.value = false;
                    return;
                }
            }
        }

        const result = await post('/unit/applications', {
            name: form.value.name,
            type: form.value.type,
            config: JSON.stringify(config),
            description: form.value.description
        });
        
        if (result.success) {
            showAlert('Application created successfully', 'success');
            router.push('/unit/applications');
        } else {
            showAlert(result.error || 'Failed to create application', 'error');
        }
    } catch (error) {
        showAlert('Failed to create application', 'error');
    } finally {
        loading.value = false;
    }
};
</script>
