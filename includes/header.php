<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Mandiri - Sistem Informasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>

<body class="bg-slate-100 text-gray-800 antialiased font-sans flex flex-col min-h-screen">

    <nav class="bg-blue-600 shadow-md text-white sticky top-0 z-50">
        <div class="mx-auto px-6 py-3 flex justify-between items-center">
            <a class="flex items-center space-x-2 font-bold text-lg tracking-wide" href="/index.php">
                <i class="bi bi-heart-pulse-fill text-xl text-red-200"></i>
                <span>Klinik Mandiri</span>
            </a>
            <div class="flex items-center space-x-4">
                <div class="text-sm text-blue-100">
                    Halo, <span class="font-semibold text-white"><?= isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'User'; ?></span>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex flex-1 pt-0">