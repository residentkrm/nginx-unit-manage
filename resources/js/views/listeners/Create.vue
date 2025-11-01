<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1
                    class="text-3xl font-semibold text-gray-900 dark:text-white"
                >
                    Create Listener
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
                            for="address"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Address</label
                        >
                        <input
                            id="address"
                            v-model="form.address"
                            type="text"
                            required
                            placeholder="*:8080"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                        />
                        <p
                            class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                        >
                            Format: *:8080, 127.0.0.1:80, [::1]:8080, or
                            unix:@abstract_socket
                        </p>
                    </div>
                    <div
                        class="border-t border-gray-200 dark:border-gray-700 pt-6"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        >
                            Basic Configuration
                        </h3>
                        <div>
                            <label
                                for="pass"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Pass <span class="text-red-500">*</span></label
                            >
                            <input
                                id="pass"
                                v-model="form.pass"
                                type="text"
                                required
                                placeholder="applications/myapp"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                            />
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Destination: applications/myapp, routes/myroute,
                                routes, upstreams/rr-lb
                            </p>
                        </div>
                        <div class="mt-4">
                            <label
                                for="backlog"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Backlog (optional)</label
                            >
                            <input
                                id="backlog"
                                v-model.number="form.backlog"
                                type="number"
                                min="-1"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                            />
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Controls the backlog parameter to listen(2).
                                Default: -1 (OS default) on
                                Linux/FreeBSD/OpenBSD/macOS, 511 on others.
                            </p>
                        </div>
                    </div>
                    <div
                        class="border-t border-gray-200 dark:border-gray-700 pt-6"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        >
                            TLS/SSL Configuration
                        </h3>
                        <div>
                            <label
                                for="tlsCertificate"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Certificate (optional)</label
                            >
                            <textarea
                                id="tlsCertificate"
                                v-model="form.tlsCertificate"
                                rows="3"
                                placeholder='["bundle1", "bundle2"] or "bundle"'
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"
                            ></textarea>
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Certificate bundle name(s) as string or JSON
                                array of strings
                            </p>
                        </div>
                        <div class="mt-4">
                            <label
                                for="tlsConfCommands"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >OpenSSL Configuration Commands (JSON,
                                optional)</label
                            >
                            <textarea
                                id="tlsConfCommands"
                                v-model="form.tlsConfCommands"
                                rows="4"
                                placeholder='{"ciphersuites": "TLS_AES_128_GCM_SHA256", "minprotocol": "TLSv1.3"}'
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"
                            ></textarea>
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                OpenSSL configuration commands (requires OpenSSL
                                1.0.2+)
                            </p>
                        </div>
                        <div
                            class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4"
                        >
                            <h4
                                class="text-md font-medium text-gray-900 dark:text-white mb-3"
                            >
                                TLS Session
                            </h4>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div>
                                    <label
                                        for="tlsSessionCacheSize"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Cache Size (optional)</label
                                    >
                                    <input
                                        id="tlsSessionCacheSize"
                                        v-model.number="
                                            form.tlsSessionCacheSize
                                        "
                                        type="number"
                                        min="0"
                                        placeholder="0"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                                    />
                                    <p
                                        class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        Default: 0 (disabled)
                                    </p>
                                </div>
                                <div>
                                    <label
                                        for="tlsSessionTimeout"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Timeout (seconds, optional)</label
                                    >
                                    <input
                                        id="tlsSessionTimeout"
                                        v-model.number="form.tlsSessionTimeout"
                                        type="number"
                                        min="0"
                                        placeholder="300"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                                    />
                                    <p
                                        class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        Default: 300
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                        >Tickets</label
                                    >
                                    <div class="flex items-center gap-4">
                                        <label class="flex items-center">
                                            <input
                                                v-model="
                                                    form.tlsSessionTicketsMode
                                                "
                                                type="radio"
                                                value="disabled"
                                                class="mr-2"
                                            />
                                            <span
                                                class="text-sm text-gray-700 dark:text-gray-300"
                                                >Disabled</span
                                            >
                                        </label>
                                        <label class="flex items-center">
                                            <input
                                                v-model="
                                                    form.tlsSessionTicketsMode
                                                "
                                                type="radio"
                                                value="enabled"
                                                class="mr-2"
                                            />
                                            <span
                                                class="text-sm text-gray-700 dark:text-gray-300"
                                                >Enabled</span
                                            >
                                        </label>
                                        <label class="flex items-center">
                                            <input
                                                v-model="
                                                    form.tlsSessionTicketsMode
                                                "
                                                type="radio"
                                                value="custom"
                                                class="mr-2"
                                            />
                                            <span
                                                class="text-sm text-gray-700 dark:text-gray-300"
                                                >Custom</span
                                            >
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-if="form.tlsSessionTicketsMode === 'custom'"
                                class="mt-4"
                            >
                                <label
                                    for="tlsSessionTickets"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Session Ticket Keys (JSON array or string,
                                    optional)</label
                                >
                                <textarea
                                    id="tlsSessionTickets"
                                    v-model="form.tlsSessionTickets"
                                    rows="4"
                                    placeholder='["key1", "key2"] or "single-key"'
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"
                                ></textarea>
                                <p
                                    class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Base64-encoded keys: AES128 (48 bytes) or
                                    AES256 (80 bytes). Array for key rotation.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div
                        class="border-t border-gray-200 dark:border-gray-700 pt-6"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        >
                            Forwarded Headers Configuration
                        </h3>
                        <div>
                            <label
                                for="forwardedSource"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Source <span class="text-red-500">*</span> (if
                                forwarded enabled)</label
                            >
                            <textarea
                                id="forwardedSource"
                                v-model="form.forwardedSource"
                                rows="3"
                                placeholder='["192.0.2.0/24", "198.51.100.0/24"] or "192.0.2.0/24"'
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm font-mono"
                            ></textarea>
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Trusted address patterns (string or JSON array).
                                Special: "unix" for UNIX sockets.
                            </p>
                        </div>
                        <div class="mt-4">
                            <label
                                for="forwardedClientIp"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Client IP Header (optional)</label
                            >
                            <input
                                id="forwardedClientIp"
                                v-model="form.forwardedClientIp"
                                type="text"
                                placeholder="X-Forwarded-For"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                            />
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                HTTP header field name for client IP
                                (X-Forwarded-For format)
                            </p>
                        </div>
                        <div class="mt-4">
                            <label
                                for="forwardedProtocol"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Protocol Header (optional)</label
                            >
                            <input
                                id="forwardedProtocol"
                                v-model="form.forwardedProtocol"
                                type="text"
                                placeholder="X-Forwarded-Proto"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 sm:text-sm"
                            />
                            <p
                                class="mt-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                HTTP header field name for protocol
                                (X-Forwarded-Proto format)
                            </p>
                        </div>
                        <div class="mt-4">
                            <label class="flex items-center">
                                <input
                                    v-model="form.forwardedRecursive"
                                    type="checkbox"
                                    class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-400"
                                />
                                <span
                                    class="ml-2 text-sm text-gray-700 dark:text-gray-300"
                                    >Recursive</span
                                >
                            </label>
                            <p
                                class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Inspect all client_ip fields in reverse order
                                (default: false)
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
                            for="addressJson"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Address</label
                        >
                        <input
                            id="addressJson"
                            v-model="form.address"
                            type="text"
                            required
                            placeholder="*:8080"
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
                        to="/unit/listeners"
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
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useListeners } from "../../composables/unit/useListeners";
import { useAlert } from "../../composables/useAlert";

const { createListener } = useListeners();
const { showAlert } = useAlert();
const router = useRouter();
const loading = ref(false);
const activeTab = ref("form");
const form = ref({
    address: "",
    pass: "",
    backlog: null,
    tlsCertificate: "",
    tlsConfCommands: "",
    tlsSessionCacheSize: null,
    tlsSessionTimeout: null,
    tlsSessionTicketsMode: "disabled",
    tlsSessionTickets: "",
    forwardedSource: "",
    forwardedClientIp: "",
    forwardedProtocol: "",
    forwardedRecursive: false,
    description: "",
});
const jsonConfig = ref("{}");
const syncToJson = () => {
    const config = {};
    if (form.value.pass) {
        config.pass = form.value.pass;
    }
    if (form.value.backlog !== null && form.value.backlog !== "") {
        config.backlog = form.value.backlog;
    }
    // TLS configuration
    const tls = {};
    if (form.value.tlsCertificate) {
        try {
            const cert = JSON.parse(form.value.tlsCertificate);
            tls.certificate = Array.isArray(cert) ? cert : [cert];
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
    if (
        form.value.tlsSessionCacheSize !== null &&
        form.value.tlsSessionCacheSize !== ""
    ) {
        session.cache_size = form.value.tlsSessionCacheSize;
    }
    if (
        form.value.tlsSessionTimeout !== null &&
        form.value.tlsSessionTimeout !== ""
    ) {
        session.timeout = form.value.tlsSessionTimeout;
    }
    if (form.value.tlsSessionTicketsMode === "enabled") {
        session.tickets = true;
    } else if (
        form.value.tlsSessionTicketsMode === "custom" &&
        form.value.tlsSessionTickets
    ) {
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
    if (
        form.value.forwardedSource ||
        form.value.forwardedClientIp ||
        form.value.forwardedProtocol
    ) {
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
const submit = async () => {
    loading.value = true;
    try {
        let config;
        if (activeTab.value === "json") {
            try {
                config = JSON.parse(jsonConfig.value);
            } catch (e) {
                showAlert("Invalid JSON format", "error");
                loading.value = false;
                return;
            }
        } else {
            if (!form.value.pass) {
                showAlert("Pass is required", "error");
                loading.value = false;
                return;
            }
            config = {};
            config.pass = form.value.pass;
            if (form.value.backlog !== null && form.value.backlog !== "") {
                config.backlog = form.value.backlog;
            }
            // TLS configuration
            if (
                form.value.tlsCertificate ||
                form.value.tlsConfCommands ||
                form.value.tlsSessionCacheSize !== null ||
                form.value.tlsSessionTimeout !== null ||
                form.value.tlsSessionTicketsMode !== "disabled"
            ) {
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
                        tls.conf_commands = JSON.parse(
                            form.value.tlsConfCommands,
                        );
                    } catch (e) {
                        showAlert(
                            "Invalid TLS conf_commands JSON format",
                            "error",
                        );
                        loading.value = false;
                        return;
                    }
                }
                // TLS Session
                const session = {};
                if (
                    form.value.tlsSessionCacheSize !== null &&
                    form.value.tlsSessionCacheSize !== ""
                ) {
                    session.cache_size = form.value.tlsSessionCacheSize;
                }
                if (
                    form.value.tlsSessionTimeout !== null &&
                    form.value.tlsSessionTimeout !== ""
                ) {
                    session.timeout = form.value.tlsSessionTimeout;
                }
                if (form.value.tlsSessionTicketsMode === "enabled") {
                    session.tickets = true;
                } else if (
                    form.value.tlsSessionTicketsMode === "custom" &&
                    form.value.tlsSessionTickets
                ) {
                    try {
                        const tickets = JSON.parse(
                            form.value.tlsSessionTickets,
                        );
                        session.tickets = Array.isArray(tickets)
                            ? tickets
                            : tickets;
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
            if (
                form.value.forwardedSource ||
                form.value.forwardedClientIp ||
                form.value.forwardedProtocol
            ) {
                const forwarded = {};
                if (!form.value.forwardedSource) {
                    showAlert(
                        "Source is required when forwarded is enabled",
                        "error",
                    );
                    loading.value = false;
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
        await createListener({
            address: form.value.address,
            pass: form.value.pass,
            config: JSON.stringify(config),
            description: form.value.description,
        });
        router.push("/unit/listeners");
    } catch (error) {
    } finally {
        loading.value = false;
    }
};
</script>
