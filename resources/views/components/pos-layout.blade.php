<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'POS Kasir - Sibangun' }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&amp;family=JetBrains+Mono:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            "darkMode": "class",
            "theme": {
                "extend": {
                    "colors": {
                        "primary": "#091426",
                        "primary-container": "#1e293b",
                        "on-primary": "#ffffff",
                        "secondary": "#904d00",
                        "secondary-container": "#fe932c",
                        "on-secondary": "#ffffff",
                        "surface": "#f8f9ff",
                        "surface-container": "#e5eeff",
                        "surface-container-low": "#f1f5fd",
                        "surface-container-high": "#dce9ff",
                        "surface-container-highest": "#d3e4fe",
                        "surface-container-lowest": "#ffffff",
                        "outline": "#75777d",
                        "outline-variant": "#cbd5e1",
                        "on-surface": "#0b1c30",
                        "on-surface-variant": "#45474c",
                        "error": "#ba1a1a",
                        "error-container": "#ffdad6"
                    },
                    "fontFamily": {
                        "sans": ["Poppins", "sans-serif"],
                        "mono": ["JetBrains Mono", "monospace"]
                    }
                }
            }
        };
    </script>
    <style>
        body,
        button,
        input,
        select,
        textarea,
        h1,
        h2,
        h3,
        h4,
        span,
        p,
        a {
            font-family: 'Poppins', sans-serif;
        }

        .font-mono-num {
            font-family: 'JetBrains Mono', monospace;
        }

        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5fd;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full antialiased font-sans bg-gray-100 text-gray-900">
    {{ $slot }}

    @livewireScripts
</body>
</html>
