<!DOCTYPE html>
<html>
<head>
    <title>Custom Validation</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @viteReactRefresh
    @vite('resources/js/app.jsx')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div id="app"></div>
</body>
</html>
