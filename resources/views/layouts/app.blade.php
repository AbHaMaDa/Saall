<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تطبيق سَل - للأسئلة والفتاوى</title>
    <link rel="icon" type="image/x-icon" href="icon2.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $assetVersion = env('VERCEL_GIT_COMMIT_SHA')
            ?: env('APP_ASSET_VERSION')
            ?: @filemtime(public_path('assets/js/script.js'))
            ?: time();
        $assetVersion = substr((string) $assetVersion, 0, 12);
    @endphp

    <link
        href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@300;400;600;700&display=swap"rel="stylesheet">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css?v={{ $assetVersion }}">
    <link rel="stylesheet" href="/assets/css/signing.css?v={{ $assetVersion }}">



</head>


<body>

    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js?v={{ $assetVersion }}"></script>



</body>

</html>
