@extends('layouts.dahsboard')

@section('content')
<style>
/* 🎨 Tema UI Modern */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
body {
    background: linear-gradient(135deg, #eef3f9 0%, #f9fbfd 100%);
    font-family: 'Inter', sans-serif;
}
.card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    padding: 30px;
    margin-top: 30px;
}
.card h3 {
    color: #0066ff;
    font-weight: 700;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}
.card h3::before {
    content: "📘";
    margin-right: 10px;
}
label {
    font-weight: 600;
    color: #333;
}
input[type="text"], input[type="number"], select {
    border: 1px solid #d3d9e3;
    border-radius: 8px;
    padding: 10px;
    width: 100%;
    transition: 0.2s;
}
input:focus, select:focus {
    border-color: #0066ff;
    outline: none;
    box-shadow: 0 0 4px rgba(0,102,255,0.3);
}
.btn-primary {
    background: #0066ff;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 600;
    transition: 0.3s;
}
.btn-primary:hover {
    background: #004ecc;
}
.btn-secondary {
    background: #f3f5f9;
    color: #333;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 600;
    transition: 0.3s;
}
.btn-secondary:hover {
    background: #e1e5ec;
}
</style>

<div class="container">
    <div class="card">
        <h3>Edit Buku</h3>

        <form action="{{ route('buku.update', $buku->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="judul">Judul Buku</label>
                <input type="text" name="judul" id="judul" value="{{ $buku->judul }}" required>
            </div>

            <div class="mb-3">
                <label for="kategori_id">Kategori</label>
                <select name="kategori_id" id="kategori_id" required>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}"
                            {{ $kategori->id == $buku->kategori_id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="stok">Stok</label>
                <input type="number" name="stok" id="stok" value="{{ $buku->stok }}" required>
            </div>

            <div class="mb-3">
                <label for="tahun">Tahun</label>
                <input type="number" name="tahun" id="tahun" value="{{ $buku->tahun }}" required>
            </div>

            <div class="mb-3">
                <label for="pengarang_id">Pengarang (boleh lebih dari satu)</label>
                <select name="pengarang_id[]" id="pengarang_id " class="form-select js-multiple" multiple required>
                    @foreach ($pengarangs as $pengarang)
                        <option value="{{ $pengarang->id }}"
                            {{ in_array($pengarang->id, $buku->pengarangs->pluck('id')->toArray()) ? 'selected' : '' }}>
                            {{ $pengarang->nama_pengarang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-top: 25px;">
                <button type="submit" class="btn-primary">💾 Simpan Perubahan</button>
                <a href="{{ route('buku.index') }}" class="btn-secondary">⬅ Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection