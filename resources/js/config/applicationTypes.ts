export const APPLICATION_TYPES = {
    PHP: "php",
    EXTERNAL: "external",
    PERL: "perl",
    PYTHON: "python",
    RUBY: "ruby",
    JAVA: "java",
    GO: "go",
    WASM: "wasm",
} as const;

export const TYPE_DESCRIPTION =
    'Application type: external, java, perl, php, python, ruby, wasm. You can specify version: "php 7", "python 3.4", etc.';

export type ApplicationType =
    (typeof APPLICATION_TYPES)[keyof typeof APPLICATION_TYPES];

export interface ApplicationForm {
    type: string;
    processesMode: string;
    processes?: number | null;
    processesMax?: number | null;
    processesSpare?: number | null;
    processesIdleTimeout?: number | null;
    root?: string;
    workingDirectory?: string;
    script?: string;
    index?: string;
    executable?: string;
    arguments?: string;
    threads?: number | null;
    threadStackSize?: number | null;
    phpOptions?: string;
    targets?: string;
    user?: string;
    group?: string;
    limitsTimeout?: number | null;
    limitsRequests?: number | null;
    stdout?: string;
    stderr?: string;
    environment?: string;
}

export interface FieldConfig {
    label: string;
    required?: boolean;
    placeholder?: string;
    description?: string;
    type?: string;
}

export interface TypeConfig {
    requiredFields: string[];
    optionalFields: string[];
    fields: Record<string, FieldConfig>;
    buildConfig: (form: ApplicationForm) => Record<string, unknown>;
    validate: (form: ApplicationForm) => string[];
}

export const TYPE_CONFIG: Record<string, TypeConfig> = {
    [APPLICATION_TYPES.PHP]: {
        requiredFields: ["root"],
        optionalFields: ["script", "index", "options", "targets"],
        fields: {
            root: {
                label: "Root",
                required: true,
                placeholder: "/var/www/html",
                description: "Base directory of the app's file structure",
            },
            script: {
                label: "Script (optional)",
                placeholder: "index.php",
                description:
                    "If set, all requests are handled by this script. Otherwise, requests are served by URI paths.",
            },
            index: {
                label: "Index (optional)",
                placeholder: "index.php",
                description:
                    "Filename added to URI paths that point to directories (default: index.php)",
            },
            phpOptions: {
                label: "PHP Options (JSON, optional)",
                type: "textarea",
                placeholder:
                    '{"file": "/etc/php.ini", "admin": {"memory_limit": "256M"}, "user": {"display_errors": "0"}}',
                description:
                    "PHP configuration: file (php.ini path), admin (PHP_INI_SYSTEM), user (PHP_INI_USER)",
            },
            targets: {
                label: "Targets (JSON, optional)",
                type: "textarea",
                placeholder:
                    '{"front": {"script": "front.php", "root": "/www/app/front/"}, "back": {"script": "back.php", "root": "/www/app/back/"}}',
                description:
                    "Up to 254 entry points. Cannot be used with root/script/index at app level.",
            },
        },
        buildConfig: (form: ApplicationForm) => {
            const config: Record<string, unknown> = {};
            if (form.root) config.root = form.root;
            if (form.script) config.script = form.script;
            if (form.index) config.index = form.index;
            if (form.phpOptions) {
                try {
                    config.options = JSON.parse(form.phpOptions);
                } catch (e) {
                    throw new Error("Invalid PHP options JSON format");
                }
            }
            if (form.targets) {
                try {
                    config.targets = JSON.parse(form.targets);
                } catch (e) {
                    throw new Error("Invalid targets JSON format");
                }
            }
            return config;
        },
        validate: (form: ApplicationForm) => {
            const errors: string[] = [];
            if (!form.root) {
                errors.push("Root is required for PHP applications");
            }
            return errors;
        },
    },
    [APPLICATION_TYPES.EXTERNAL]: {
        requiredFields: ["executable"],
        optionalFields: ["arguments", "working_directory"],
        fields: {
            executable: {
                label: "Executable",
                required: true,
                placeholder: "app.js",
                description:
                    "Pathname of the app, absolute or relative to working_directory. Make sure to chmod +x the file.",
            },
            arguments: {
                label: "Arguments (JSON array, optional)",
                type: "textarea",
                placeholder: '["--tmp-files", "/tmp/node-cache"]',
                description: "Command-line arguments to be passed to the app",
            },
        },
        buildConfig: (form: ApplicationForm) => {
            const config: Record<string, unknown> = {};
            if (form.executable) config.executable = form.executable;
            if (form.arguments) {
                try {
                    config.arguments = JSON.parse(form.arguments);
                } catch (e) {
                    throw new Error("Invalid arguments JSON format");
                }
            }
            return config;
        },
        validate: (form: ApplicationForm) => {
            const errors: string[] = [];
            if (!form.executable) {
                errors.push("Executable is required for external applications");
            }
            return errors;
        },
    },
    [APPLICATION_TYPES.PERL]: {
        requiredFields: ["script"],
        optionalFields: ["threads", "thread_stack_size", "working_directory"],
        fields: {
            script: {
                label: "Script (PSGI)",
                required: true,
                placeholder: "/www/bugtracker/app.psgi",
                description: "PSGI script path",
            },
            threads: {
                label: "Threads (optional)",
                type: "number",
                placeholder: "1",
                description:
                    "Number of worker threads per app process (default: 1)",
            },
            threadStackSize: {
                label: "Thread Stack Size (bytes, optional)",
                type: "number",
                description:
                    "Stack size of a worker thread (multiple of memory page size)",
            },
        },
        buildConfig: (form: ApplicationForm) => {
            const config: Record<string, unknown> = {};
            if (form.script) config.script = form.script;
            if (form.threads) config.threads = form.threads;
            if (form.threadStackSize)
                config.thread_stack_size = form.threadStackSize;
            return config;
        },
        validate: (form: ApplicationForm) => {
            const errors: string[] = [];
            if (!form.script) {
                errors.push("Script is required for Perl applications");
            }
            return errors;
        },
    },
    default: {
        requiredFields: [],
        optionalFields: ["root", "script", "working_directory"],
        fields: {
            root: {
                label: "Root (optional)",
                placeholder: "/var/www/html",
            },
            script: {
                label: "Script (optional)",
                placeholder: "index.php",
            },
        },
        buildConfig: (form: ApplicationForm) => {
            const config: Record<string, unknown> = {};
            if (form.root) config.root = form.root;
            if (form.script) config.script = form.script;
            return config;
        },
        validate: () => [],
    },
};

export const getBaseType = (type: string): ApplicationType => {
    const normalizedType = type.toLowerCase().trim();
    if (normalizedType.startsWith(APPLICATION_TYPES.PHP))
        return APPLICATION_TYPES.PHP;
    if (normalizedType.startsWith(APPLICATION_TYPES.EXTERNAL))
        return APPLICATION_TYPES.EXTERNAL;
    if (normalizedType.startsWith(APPLICATION_TYPES.PERL))
        return APPLICATION_TYPES.PERL;
    if (normalizedType.startsWith(APPLICATION_TYPES.PYTHON))
        return APPLICATION_TYPES.PYTHON;
    if (normalizedType.startsWith(APPLICATION_TYPES.RUBY))
        return APPLICATION_TYPES.RUBY;
    if (normalizedType.startsWith(APPLICATION_TYPES.JAVA))
        return APPLICATION_TYPES.JAVA;
    if (normalizedType.startsWith(APPLICATION_TYPES.GO))
        return APPLICATION_TYPES.GO;
    if (normalizedType.startsWith(APPLICATION_TYPES.WASM))
        return APPLICATION_TYPES.WASM;
    return normalizedType.split(" ")[0] as ApplicationType;
};

export const getTypeConfig = (
    baseType: ApplicationType | string,
): TypeConfig => {
    return TYPE_CONFIG[baseType] || TYPE_CONFIG.default;
};

export const buildCommonConfig = (
    form: ApplicationForm,
): Record<string, unknown> => {
    const config: Record<string, unknown> = {};

    if (form.workingDirectory) {
        config.working_directory = form.workingDirectory;
    }
    if (form.user) {
        config.user = form.user;
    }
    if (form.group) {
        config.group = form.group;
    }

    const limits: Record<string, unknown> = {};
    if (form.limitsTimeout !== null && form.limitsTimeout !== undefined) {
        limits.timeout = form.limitsTimeout;
    }
    if (form.limitsRequests !== null && form.limitsRequests !== undefined) {
        limits.requests = form.limitsRequests;
    }
    if (Object.keys(limits).length > 0) {
        config.limits = limits;
    }

    if (form.stdout) {
        config.stdout = form.stdout;
    }
    if (form.stderr) {
        config.stderr = form.stderr;
    }

    if (form.environment) {
        try {
            config.environment = JSON.parse(form.environment);
        } catch (e) {
            throw new Error("Invalid environment variables JSON format");
        }
    }

    if (form.processesMode === "static") {
        if (form.processes) {
            config.processes = form.processes;
        }
    } else {
        const processesObj: Record<string, unknown> = {};
        if (form.processesMax) processesObj.max = form.processesMax;
        if (form.processesSpare) processesObj.spare = form.processesSpare;
        if (form.processesIdleTimeout)
            processesObj.idle_timeout = form.processesIdleTimeout;
        if (Object.keys(processesObj).length > 0) {
            config.processes = processesObj;
        }
    }

    return config;
};

export const buildFullConfig = (
    baseType: ApplicationType | string,
    form: ApplicationForm,
): Record<string, unknown> => {
    const typeConfig = getTypeConfig(baseType);
    const config = {
        ...typeConfig.buildConfig(form),
        ...buildCommonConfig(form),
    };
    return config;
};
