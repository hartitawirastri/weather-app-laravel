<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Pengecek Cuaca</title>

    {{-- Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-sky-400 to-blue-700 flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">

        {{-- Judul Aplikasi --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">🌤️ Cek Cuaca</h1>
            <p class="text-gray-500 mt-1 text-sm">Masukkan nama kota untuk melihat cuaca terkini</p>
        </div>

        {{-- Form Input Kota --}}
        <form action="{{ route('weather.check') }}" method="POST">
            @csrf

            <div class="flex gap-2">
                <input
                    type="text"
                    name="city"
                    value="{{ old('city', request('city')) }}"
                    placeholder="Contoh: Jakarta, Pekanbaru..."
                    class="flex-1 border border-gray-300 rounded-lg px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-400 transition"
                >
                <button
                    type="submit"
                    class="bg-sky-500 hover:bg-sky-600 text-white font-semibold px-5 py-3 rounded-lg transition"
                >
                    Cek
                </button>
            </div>

            {{-- Pesan Validasi Error --}}
            @error('city')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </form>

        {{-- Area Hasil: Pesan Error dari API --}}
        @if(isset($errorMessage))
            <div class="mt-6 bg-red-50 border border-red-200 rounded-xl p-4 text-center">
                <p class="text-red-600 font-medium">❌ {{ $errorMessage }}</p>
            </div>
        @endif

        {{-- Area Hasil: Data Cuaca --}}
        @if(isset($weather))
            <div class="mt-6 bg-sky-50 border border-sky-200 rounded-xl p-6 text-center">

                {{-- Ikon Cuaca dari OpenWeatherMap --}}
                <img
                    src="https://openweathermap.org/img/wn/{{ $weather['icon'] }}@2x.png"
                    alt="Ikon Cuaca"
                    class="mx-auto w-20 h-20"
                >

                {{-- Nama Kota & Negara --}}
                <h2 class="text-2xl font-bold text-gray-800 mt-2">
                    {{ $weather['city'] }}, {{ $weather['country'] }}
                </h2>

                {{-- Suhu --}}
                <p class="text-6xl font-extrabold text-sky-600 mt-3">
                    {{ $weather['temperature'] }}°C
                </p>

                {{-- Deskripsi Cuaca --}}
                <p class="text-gray-500 mt-2 text-lg capitalize">
                    {{ $weather['description'] }}
                </p>

            </div>
        @endif

    </div>

</body>
</html>
