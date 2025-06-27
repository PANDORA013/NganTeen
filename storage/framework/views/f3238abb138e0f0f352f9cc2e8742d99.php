<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nganteen - Solusi Mager Antri</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="antialiased">
    <header class="flex items-center justify-between px-8 py-4 border-b border-gray-100">
        <a href="#" class="flex items-center font-bold text-xl text-black">
            <img src="https://cdn-icons-png.flaticon.com/512/1046/1046857.png" alt="Nganteen Logo" class="w-8 h-8 mr-2">
            NGANTEEN
        </a>
        <nav class="flex gap-6">
            <a href="#" class="font-semibold text-gray-600 hover:text-black transition">Dashboard</a>
            <a href="#" class="font-semibold text-gray-600 hover:text-black transition">Service</a>
            <a href="#" class="font-semibold text-gray-600 hover:text-black transition">Contact</a>
            <a href="#" class="font-semibold text-gray-600 hover:text-black transition">Support</a>
        </nav>
        <a href="<?php echo e(route('register')); ?>" class="bg-black text-white px-5 py-2 rounded font-bold hover:bg-gray-800 transition">
            Sign Up
        </a>
    </header>

    <main class="max-w-2xl mx-auto px-8 py-16">
        <h1 class="text-5xl font-black mb-4">
            Solusi mager antri
        </h1>
        <p class="text-gray-700 text-lg mb-8 leading-relaxed">
            nganteen di buat dan di rancang oleh manusia-manusia yang malas ngantri dan sempit sempitan di kantin, kena ketek basah mahasiswi si paling mager ya guys. that's why this solution was come for youuuuu!!!! #yangmagermageraja
        </p>
        <a href="<?php echo e(route('login')); ?>" class="inline-block bg-black text-white px-8 py-3 rounded-lg font-bold text-lg hover:bg-gray-800 transition">
            Login
        </a>
    </main>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\NganTeen-main\resources\views/welcome.blade.php ENDPATH**/ ?>