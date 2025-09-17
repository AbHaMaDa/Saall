<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تطبيق سَل - للأسئلة والفتاوى</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('icon2.png') }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@300;400;600;700&display=swap"rel="stylesheet">
    <meta charset="UTF-8">
    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/style.css', 'resources/js/script.js','resources/css/signing.css'])
    @endif
</head>


<body>

        @yield('content')


</body>

</html>
