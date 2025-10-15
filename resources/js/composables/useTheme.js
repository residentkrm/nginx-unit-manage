import { ref, watch } from 'vue';

const isDark = ref(false);

const initTheme = () => {
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    isDark.value = savedTheme === 'dark' || (!savedTheme && prefersDark);
    updateHtmlClass();
};

const updateHtmlClass = () => {
    if (isDark.value) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
};

watch(isDark, () => {
    updateHtmlClass();
    localStorage.setItem('theme', isDark.value ? 'dark' : 'light');
});

if (typeof window !== 'undefined') {
    initTheme();
}

export const useTheme = () => {
    const toggleTheme = () => {
        isDark.value = !isDark.value;
    };

    return {
        isDark,
        toggleTheme,
    };
};

