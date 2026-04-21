<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Pengecek Cuaca</title>

    {{-- Bootstrap 5 via CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            min-height: 100vh;
        }
        .card-weather {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
        }
        .temp-display {
            font-size: 4rem;
            font-weight: 700;
            color: #0984e3;
        }
        .table-history th {
            background-color: #0984e3;
            color: white;
        }
    </style>
</head>
<body class="py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- ===== KARTU UTAMA: FORM & HASIL CUACA ===== --}}
            <div class="card card-weather mb-4">
                <div class="card-body p-4">

                    {{-- Judul --}}
                    <h2 class="card-title text-center fw-bold mb-1">🌤️ Pengecek Cuaca</h2>
                    <p class="text-center text-muted small mb-4">Masukkan nama kota untuk melihat cuaca terkini</p>

                    {{-- Form Input --}}
                    <form action="{{ route('weather.check') }}" method="POST">
                        @csrf
                        <div class="input-group mb-2">
                            <input
                                type="text"
                                name="city"
                                class="form-control form-control-lg @error('city') is-invalid @enderror"
                                placeholder="Contoh: Jakarta, Pekanbaru, Bandung..."
                                value="{{ old('city') }}"
                            >
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                Cek Cuaca
                            </button>
                        </div>
                        {{-- Pesan error validasi --}}
                        @error('city')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </form>

                    {{-- Pesan sukses setelah hapus --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            ✅ {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Pesan error dari API --}}
                    @if(isset($errorMessage))
                        <div class="alert alert-danger mt-3">
                            ❌ {{ $errorMessage }}
                        </div>
                    @endif

                    {{-- Hasil Data Cuaca --}}
                    @if(isset($weather))
                        <hr class="my-4">
                        <div class="text-center">
                            <img
                                src="https://openweathermap.org/img/wn/{{ $weather['icon'] }}@2x.png"
                                alt="Ikon Cuaca"
                                width="80"
                            >
                            <h3 class="fw-bold mb-0 mt-1">
                                {{ $weather['city'] }}, {{ $weather['country'] }}
                            </h3>
                            <div class="temp-display my-2">{{ $weather['temperature'] }}°C</div>
                            <p class="text-muted fs-5">{{ $weather['description'] }}</p>
                        </div>
                    @endif

                </div>
            </div>

            {{-- ===== TABEL RIWAYAT PENCARIAN ===== --}}
            <div class="card card-weather">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">📋 Riwayat Pencarian</h5>

                    @if($histories->isEmpty())
                        <p class="text-muted text-center py-3">
                            Belum ada riwayat pencarian. Coba cek cuaca kota pertama Anda!
                        </p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle table-history">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Kota</th>
                                        <th>Suhu (°C)</th>
                                        <th>Deskripsi</th>
                                        <th>Waktu Pencarian</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($histories as $index => $history)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="fw-semibold">{{ $history->city_name }}</td>
                                            <td>{{ $history->temperature }}°C</td>
                                            <td>{{ $history->description }}</td>
                                            <td class="text-muted small">
                                                {{ $history->created_at->format('d M Y, H:i') }}
                                            </td>
                                            <td>
                                                {{-- Tombol Hapus menggunakan method spoofing DELETE --}}
                                                <form
                                                    action="{{ route('weather.destroy', $history->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus riwayat kota {{ $history->city_name }}?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        🗑️ Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
            {{-- ===== END TABEL RIWAYAT ===== --}}

        </div>
    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>  
</html>
