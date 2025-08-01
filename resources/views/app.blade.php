<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-root">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nginx Unit Manager - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Configure Tailwind for dark mode
        tailwind.config = {
            darkMode: 'class',
        };
        
        // Initialize theme from localStorage
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            const html = document.getElementById('html-root');
            if (theme === 'dark') {
                html.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div id="app"></div>
</body>
</html>

