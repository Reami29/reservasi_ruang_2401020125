<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Reservasi Ruang</title>
</head>
<body>
    <h1>Sistem Reservasi Ruang Kampus</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <!-- Form Pencarian & Filter -->
    <form method="GET" action="{{ route('reservasi.index') }}">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari kode/nama/email...">
        <select name="ruang_id">
            <option value="">-- Semua Ruang --</option>
            @foreach($ruangs as $r)
                <option value="{{ $r->id }}" {{ (isset($ruangFilter) && $ruangFilter == $r->id) ? 'selected' : '' }}>
                    {{ $r->nama_ruang }}
                </option>
            @endforeach
        </select>
        <button type="submit">Cari & Filter</button>
        <a href="{{ route('reservasi.index') }}">Reset</a>
    </form>
    <hr>

    <!-- Form Tambah Data -->
    <h3>Tambah Reservasi Baru</h3>
    <form action="{{ route('reservasi.store') }}" method="POST">
        @csrf
        <p>Kode Reservasi (10 Karakter):<br><input type="text" name="kode_reservasi" value="{{ old('kode_reservasi') }}" required></p>
        <p>Nama Peminjam:<br><input type="text" name="nama_peminjam" value="{{ old('nama_peminjam') }}" required></p>
        <p>Email:<br><input type="email" name="email" value="{{ old('email') }}" required></p>
        <p>Durasi Jam (1 - 8):<br><input type="number" name="durasi_jam" value="{{ old('durasi_jam') }}" min="1" max="8" required></p>
        <p>Ruang:<br>
            <select name="ruang_id" required>
                <option value="">-- Pilih Ruang --</option>
                @foreach($ruangs as $r)
                    <option value="{{ $r->id }}" {{ old('ruang_id') == $r->id ? 'selected' : '' }}>{{ $r->nama_ruang }}</option>
                @endforeach
            </select>
        </p>
        <button type="submit">Simpan Reservasi</button>
    </form>
    <hr>

    <!-- Tabel Data -->
    <h3>Daftar Reservasi Ruang</h3>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Peminjam</th>
                <th>Email</th>
                <th>Durasi (Jam)</th>
                <th>Status Durasi</th>
                <th>Ruang</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservasis as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->kode_reservasi }}</td>
                <td>{{ $item->nama_peminjam }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->durasi_jam }}</td>
                <td>
                    @php
                        $controller = new App\Http\Controllers\ReservasiRuangController();
                        $status = $controller->statusDurasi($item->durasi_jam);
                    @endphp
                    @if($status == 'Panjang')
                        <strong style="color: blue;">Panjang</strong>
                    @else
                        <span style="color: green;">Singkat</span>
                    @endif
                </td>
                <td>{{ $item->nama_ruang }}</td>
                <td>
                    <a href="{{ route('reservasi.edit', $item->id) }}">Edit</a>
                    <form action="{{ route('reservasi.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>