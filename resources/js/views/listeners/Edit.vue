<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">Edit Listener: {{ address }}</h1>
            </div>
        </div>

        <div v-if="loading" class="mt-8 text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>

        <form v-else @submit.prevent="submit" class="mt-8">
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
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Basic Configuration</h3>
                        
                        <div>
                            <label for="pass" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pass <span class="text-red-500">*</span></label>
                            <input v-model="form.pass" type="text" id="pass" required placeholder="applications/myapp" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Destination: applications/myapp, routes/myroute, routes, upstreams/rr-lb</p>
                        </div>

                        <div class="mt-4">
                            <label for="backlog" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Backlog (optional)</label>
                            <input v-model.number="form.backlog" type="number" id="backlog" min="-1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Controls the backlog parameter to listen(2). Default: -1 (OS default) on Linux/FreeBSD/OpenBSD/macOS, 511 on others.</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">TLS/SSL Configuration</h3>
                        
                        <div>
                            <label for="tlsCertificate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Certificate (optional)</label>
                            <textarea v-model="form.tlsCertificate" id="tlsCertificate" rows="3" placeholder='["bundle1", "bundle2"] or "bundle"' class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"></textarea>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Certificate bundle name(s) as string or JSON array of strings</p>
                        </div>

                        <div class="mt-4">
                            <label for="tlsConfCommands" class="block text-sm font-medium text-gray-700 dark:text-gray-300">OpenSSL Configuration Commands (JSON, optional)</label>
                            <textarea v-model="form.tlsConfCommands" id="tlsConfCommands" rows="4" placeholder='{"ciphersuites": "TLS_AES_128_GCM_SHA256", "minprotocol": "TLSv1.3"}' class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"></textarea>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">OpenSSL configuration commands (requires OpenSSL 1.0.2+)</p>
                        </div>

                        <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">TLS Session</h4>
                            
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div>
                                    <label for="tlsSessionCacheSize" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cache Size (optional)</label>
                                    <input v-model.number="form.tlsSessionCacheSize" type="number" id="tlsSessionCacheSize" min="0" placeholder="0" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Default: 0 (disabled)</p>
                                </div>

                                <div>
                                    <label for="tlsSessionTimeout" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Timeout (seconds, optional)</label>
                                    <input v-model.number="form.tlsSessionTimeout" type="number" id="tlsSessionTimeout" min="0" placeholder="300" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Default: 300</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tickets</label>
                                    <div class="flex items-center gap-4">
                                        <label class="flex items-center">
                                            <input type="radio" v-model="form.tlsSessionTicketsMode" value="disabled" class="mr-2">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Disabled</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" v-model="form.tlsSessionTicketsMode" value="enabled" class="mr-2">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Enabled</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" v-model="form.tlsSessionTicketsMode" value="custom" class="mr-2">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Custom</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div v-if="form.tlsSessionTicketsMode === 'custom'" class="mt-4">
                                <label for="tlsSessionTickets" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Session Ticket Keys (JSON array or string, optional)</label>
                                <textarea v-model="form.tlsSessionTickets" id="tlsSessionTickets" rows="4" placeholder='["key1", "key2"] or "single-key"' class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"></textarea>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Base64-encoded keys: AES128 (48 bytes) or AES256 (80 bytes). Array for key rotation.</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Forwarded Headers Configuration</h3>
                        
                        <div>
                            <label for="forwardedSource" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Source <span class="text-red-500">*</span> (if forwarded enabled)</label>
                            <textarea v-model="form.forwardedSource" id="forwardedSource" rows="3" placeholder='["192.0.2.0/24", "198.51.100.0/24"] or "192.0.2.0/24"' class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"></textarea>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Trusted address patterns (string or JSON array). Special: "unix" for UNIX sockets.</p>
                        </div>

                        <div class="mt-4">
                            <label for="forwardedClientIp" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client IP Header (optional)</label>
                            <input v-model="form.forwardedClientIp" type="text" id="forwardedClientIp" placeholder="X-Forwarded-For" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">HTTP header field name for client IP (X-Forwarded-For format)</p>
                        </div>

                        <div class="mt-4">
                            <label for="forwardedProtocol" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Protocol Header (optional)</label>
                            <input v-model="form.forwardedProtocol" type="text" id="forwardedProtocol" placeholder="X-Forwarded-Proto" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm">
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">HTTP header field name for protocol (X-Forwarded-Proto format)</p>
                        </div>

                        <div class="mt-4">
                            <label class="flex items-center">
                                <input type="checkbox" v-model="form.forwardedRecursive" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-400">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Recursive</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Inspect all client_ip fields in reverse order (default: false)</p>
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
                    <router-link to="/unit/listeners" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200">Cancel</router-link>
                    <button type="submit" :disabled="saving" class="rounded-md bg-blue-600 dark:bg-blue-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 dark:focus-visible:outline-blue-500 transition-colors disabled:opacity-50">
                        {{ saving ? 'Updating...' : 'Update' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useApi } from '../../composables/useApi';
import { useAlert } from '../../composables/useAlert';

const props = defineProps({
    address: String
});

const { get, put } = useApi();
const { showAlert } = useAlert();
const route = useRoute();
const router = useRouter();
const loading = ref(true);
const saving = ref(false);
const activeTab = ref('form');

const address = computed(() => {
    const addr = props.address || route.params.address;
    return decodeURIComponent(addr);
});

const form = ref({
    pass: '',
    backlog: null,
    tlsCertificate: '',
    tlsConfCommands: '',
    tlsSessionCacheSize: null,
    tlsSessionTimeout: null,
    tlsSessionTicketsMode: 'disabled',
    tlsSessionTickets: '',
    forwardedSource: '',
    forwardedClientIp: '',
    forwardedProtocol: '',
    forwardedRecursive: false,
    description: ''
});

const jsonConfig = ref('{}');

const syncToJson = () => {
    const config = {};
    
    if (form.value.pass) {
        config.pass = form.value.pass;
    }
    
    if (form.value.backlog !== null && form.value.backlog !== '') {
        config.backlog = form.value.backlog;
    }

    // TLS configuration
    const tls = {};
    if (form.value.tlsCertificate) {
        try {
            const cert = JSON.parse(form.value.tlsCertificate);
            tls.certificate = Array.isArray(cert) ? cert : cert;
        } catch (e) {
            tls.certificate = form.value.tlsCertificate;
        }
    }
    if (form.value.tlsConfCommands) {
        try {
            tls.conf_commands = JSON.parse(form.value.tlsConfCommands);
        } catch (e) {
            // Keep as is if invalid
        }
    }
    
    // TLS Session
    const session = {};
    if (form.value.tlsSessionCacheSize !== null && form.value.tlsSessionCacheSize !== '') {
        session.cache_size = form.value.tlsSessionCacheSize;
    }
    if (form.value.tlsSessionTimeout !== null && form.value.tlsSessionTimeout !== '') {
        session.timeout = form.value.tlsSessionTimeout;
    }
    if (form.value.tlsSessionTicketsMode === 'enabled') {
        session.tickets = true;
    } else if (form.value.tlsSessionTicketsMode === 'custom' && form.value.tlsSessionTickets) {
        try {
            const tickets = JSON.parse(form.value.tlsSessionTickets);
            session.tickets = Array.isArray(tickets) ? tickets : tickets;
        } catch (e) {
            session.tickets = form.value.tlsSessionTickets;
        }
    }
    if (Object.keys(session).length > 0) {
        tls.session = session;
    }
    
    if (Object.keys(tls).length > 0) {
        config.tls = tls;
    }

    // Forwarded configuration
    if (form.value.forwardedSource || form.value.forwardedClientIp || form.value.forwardedProtocol) {
        const forwarded = {};
        if (form.value.forwardedSource) {
            try {
                const source = JSON.parse(form.value.forwardedSource);
                forwarded.source = Array.isArray(source) ? source : source;
            } catch (e) {
                forwarded.source = form.value.forwardedSource;
            }
        }
        if (form.value.forwardedClientIp) {
            forwarded.client_ip = form.value.forwardedClientIp;
        }
        if (form.value.forwardedProtocol) {
            forwarded.protocol = form.value.forwardedProtocol;
        }
        if (form.value.forwardedRecursive) {
            forwarded.recursive = true;
        }
        if (Object.keys(forwarded).length > 0) {
            config.forwarded = forwarded;
        }
    }

    jsonConfig.value = JSON.stringify(config, null, 2);
};

const loadListener = async () => {
    loading.value = true;
    const result = await get(`/unit/listeners/${encodeURIComponent(address.value)}`);
    if (result.success) {
        const listener = result.data.data || result.data;
        const config = listener.config || {};
        
        // Basic config
        form.value.pass = config.pass || listener.pass || '';
        form.value.backlog = config.backlog !== undefined ? config.backlog : null;
        
        // TLS config
        if (config.tls) {
            if (config.tls.certificate) {
                form.value.tlsCertificate = Array.isArray(config.tls.certificate) 
                    ? JSON.stringify(config.tls.certificate, null, 2)
                    : config.tls.certificate;
            }
            if (config.tls.conf_commands) {
                form.value.tlsConfCommands = JSON.stringify(config.tls.conf_commands, null, 2);
            }
            if (config.tls.session) {
                const session = config.tls.session;
                form.value.tlsSessionCacheSize = session.cache_size !== undefined ? session.cache_size : null;
                form.value.tlsSessionTimeout = session.timeout !== undefined ? session.timeout : null;
                if (session.tickets === true) {
                    form.value.tlsSessionTicketsMode = 'enabled';
                } else if (session.tickets === false || session.tickets === null || session.tickets === undefined) {
                    form.value.tlsSessionTicketsMode = 'disabled';
                } else {
                    form.value.tlsSessionTicketsMode = 'custom';
                    if (Array.isArray(session.tickets)) {
                        form.value.tlsSessionTickets = JSON.stringify(session.tickets, null, 2);
                    } else {
                        form.value.tlsSessionTickets = session.tickets;
                    }
                }
            }
        }
        
        // Forwarded config
        if (config.forwarded) {
            if (config.forwarded.source) {
                form.value.forwardedSource = Array.isArray(config.forwarded.source)
                    ? JSON.stringify(config.forwarded.source, null, 2)
                    : config.forwarded.source;
            }
            form.value.forwardedClientIp = config.forwarded.client_ip || '';
            form.value.forwardedProtocol = config.forwarded.protocol || '';
            form.value.forwardedRecursive = config.forwarded.recursive || false;
        }
        
        form.value.description = listener.description || '';
        jsonConfig.value = JSON.stringify(config, null, 2);
    } else {
        showAlert(result.error || 'Failed to load listener', 'error');
        router.push('/unit/listeners');
    }
    loading.value = false;
};

const submit = async () => {
    saving.value = true;
    try {
        let config;
        
        if (activeTab.value === 'json') {
            try {
                config = JSON.parse(jsonConfig.value);
            } catch (e) {
                showAlert('Invalid JSON format', 'error');
                saving.value = false;
                return;
            }
        } else {
            if (!form.value.pass) {
                showAlert('Pass is required', 'error');
                saving.value = false;
                return;
            }
            
            config = {};
            config.pass = form.value.pass;
            
            if (form.value.backlog !== null && form.value.backlog !== '') {
                config.backlog = form.value.backlog;
            }

            // TLS configuration
            if (form.value.tlsCertificate || form.value.tlsConfCommands || form.value.tlsSessionCacheSize !== null || form.value.tlsSessionTimeout !== null || form.value.tlsSessionTicketsMode !== 'disabled') {
                const tls = {};
                if (form.value.tlsCertificate) {
                    try {
                        const cert = JSON.parse(form.value.tlsCertificate);
                        tls.certificate = Array.isArray(cert) ? cert : cert;
                    } catch (e) {
                        tls.certificate = form.value.tlsCertificate;
                    }
                }
                if (form.value.tlsConfCommands) {
                    try {
                        tls.conf_commands = JSON.parse(form.value.tlsConfCommands);
                    } catch (e) {
                        showAlert('Invalid TLS conf_commands JSON format', 'error');
                        saving.value = false;
                        return;
                    }
                }
                
                // TLS Session
                const session = {};
                if (form.value.tlsSessionCacheSize !== null && form.value.tlsSessionCacheSize !== '') {
                    session.cache_size = form.value.tlsSessionCacheSize;
                }
                if (form.value.tlsSessionTimeout !== null && form.value.tlsSessionTimeout !== '') {
                    session.timeout = form.value.tlsSessionTimeout;
                }
                if (form.value.tlsSessionTicketsMode === 'enabled') {
                    session.tickets = true;
                } else if (form.value.tlsSessionTicketsMode === 'custom' && form.value.tlsSessionTickets) {
                    try {
                        const tickets = JSON.parse(form.value.tlsSessionTickets);
                        session.tickets = Array.isArray(tickets) ? tickets : tickets;
                    } catch (e) {
                        session.tickets = form.value.tlsSessionTickets;
                    }
                }
                if (Object.keys(session).length > 0) {
                    tls.session = session;
                }
                
                if (Object.keys(tls).length > 0) {
                    config.tls = tls;
                }
            }

            // Forwarded configuration
            if (form.value.forwardedSource || form.value.forwardedClientIp || form.value.forwardedProtocol) {
                const forwarded = {};
                if (!form.value.forwardedSource) {
                    showAlert('Source is required when forwarded is enabled', 'error');
                    saving.value = false;
                    return;
                }
                try {
                    const source = JSON.parse(form.value.forwardedSource);
                    forwarded.source = Array.isArray(source) ? source : source;
                } catch (e) {
                    forwarded.source = form.value.forwardedSource;
                }
                if (form.value.forwardedClientIp) {
                    forwarded.client_ip = form.value.forwardedClientIp;
                }
                if (form.value.forwardedProtocol) {
                    forwarded.protocol = form.value.forwardedProtocol;
                }
                if (form.value.forwardedRecursive) {
                    forwarded.recursive = true;
                }
                config.forwarded = forwarded;
            }
        }

        const result = await put(`/unit/listeners/${encodeURIComponent(address.value)}`, {
            pass: form.value.pass,
            config: JSON.stringify(config),
            description: form.value.description
        });
        
        if (result.success) {
            showAlert('Listener updated successfully', 'success');
            router.push('/unit/listeners');
        } else {
            showAlert(result.error || 'Failed to update listener', 'error');
        }
    } catch (error) {
        showAlert('Failed to update listener', 'error');
    } finally {
        saving.value = false;
    }
};

onMounted(loadListener);
</script>
