<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sibangun - Toko Bahan Bangunan Terlengkap di Pekanbaru</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#091426',
                        'primary-light': '#1e293b',
                        secondary: '#fe932c',
                        'secondary-dark': '#904d00',
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
</head>

<body class="bg-white text-gray-900">

    <!-- HEADER -->
   @include('component.header')
    <!-- HERO SECTION -->
   @include('component.hero')
    <!-- KATEGORI PRODUK -->
    @include('component.kategori')
    <!-- PRODUK UNGGULAN -->
   @include('component.product')
    <!-- KEUNGGULAN -->
  @include('component.keunggulan')
    <!-- CABANG TOKO -->
    @include('component.cabang')
    <!-- CTA KONTAK -->
    @include('component.cta')
    <!-- FOOTER -->
    @include('component.footer')

</body>

</html>
