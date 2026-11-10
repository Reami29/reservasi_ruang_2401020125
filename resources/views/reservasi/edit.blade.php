<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Reservasi Ruang</title>
</head>
<body>
    <h1>Edit Data Reservasi</h1>
    <form action="{{ route('reservasi.update', $reservasi->id) }}" method="POST">
        @csrf
        @method('PUT')
        <p>Kode Reservasi:<br><input type="text" value="{{ $reservasi->kode_reservasi }}" disabled></p>
        <p>Nama Peminjam:<br><input type="text" name="nama_peminjam" value="{{ old('nama_peminjam', $reservasi->nama_peminjam) }}" required></p>
        <p>Email:<br><input type="email" value="{{ $reservasi->email }}" disabled></p>
        <p>Durasi Jam (1 - 8):<br><input type="number" name="durasi_jam" value="{{ old('durasi_jam', $reservasi->durasi_jam) }}" min="1" max="8" required></p>
        <p>Ruang:<br>
            <select name="ruang_id" required>
                @foreach($ruangs as $r)
                    <option value="{{ $r->id }}" {{ $reservasi->ruang_id == $r->id ? 'selected' : '' }}>{{ $r->nama_ruang }}</option>
                @endforeach
            </select>
        </p>
        <button type="submit">Perbarui Reservasi</button>
        <a href="{{ route('reservasi.index') }}">Kembali</a>
    </form>
</body>
</html>