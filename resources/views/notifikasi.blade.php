<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="{{ asset('images/logoRRIS.png') }}">
    <title>Notifikasi</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 h-screen flex flex-col">

    <header class="bg-white shadow-md sticky top-0 z-10">
        <div class="h-20 flex items-center gap-3 px-4">
            <a href="" class="p-2 rounded-full hover:bg-gray-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                    stroke="currentColor" class="w-6 h-6 text-gray-700">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>

            <h3 class="text-xl font-semibold text-gray-800">
                Kembali
            </h3>
        </div>
    </header>

    <div
        class="flex items-center justify-center xl:justify-start desk:justify-start 
            w-full h-36 shadow-md 
            px-6 md:px-12 xl:px-20 bg-(--blue)">

        <h1 class="text-4xl xl:text-5xl font-(--font-heading) text-white">
            Notifikasi
        </h1>

    </div>

    <div class="flex-1 overflow-y-auto px-4 pt-4 mb-4 space-y-4">
        <div
            class="w-full max-w-3xl bg-white rounded-xl p-9 mx-auto shadow-md border border-gray-200 transition-all duration-300 ease-out hover:-translate-x-3
            hover:shadow-xl hover:border-gray-300 hover:bg-gray-50 cursor-pointer">
            <h3 class="text-lg font-semibold text-gray-800">
                Notifikasi 1
            </h3>
            <p class="text-sm text-gray-600 mt-1">
                Ini adalah isi notifikasi pertama.
            </p>
        </div>
    </div>

</body>

</html>
