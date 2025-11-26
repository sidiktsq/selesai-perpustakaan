@extends('layouts.dahsboard')

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <h4 class="fw-bold">Tambah Transaksi Baru</h4>
            <p class="text-muted">Input data peminjaman buku perpustakaan</p>
        </div>
        <div class="col text-end">
            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary btn-sm">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Error Validation -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Card Input -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <strong>Form Peminjaman</strong>
        </div>

        <div class="card-body">

            <form action="{{ route('peminjaman.store') }}" method="POST">
                @csrf

                <!-- Nama Peminjam -->
                <div class="mb-4">
                    <label class="form-label">Nama Peminjam</label>
                    <input type="text" 
                           name="nama_peminjam" 
                           class="form-control @error('nama_peminjam') is-invalid @enderror"
                           value="{{ old('nama_peminjam') }}"
                           placeholder="Masukkan nama peminjam" 
                           required>
                </div>

                <hr>
                <h5 class="fw-bold mb-3">Daftar Buku</h5>

                <!-- Wrapper Buku -->
                <div id="buku-wrapper">

                    <div class="row buku-item g-3 mb-3">

                        <div class="col-md-5">
                            <label class="form-label">Buku</label>
                            <select name="buku_id[]" class="form-select buku-select" required>
                                <option value="">-- Pilih Buku --</option>
                                @foreach ($buku as $book)
                                <option value="{{ $book->id }}" data-stok="{{ $book->stok }}">
                                    {{ optional($book->kategori)->nama_kategori }} -
                                    {{ $book->judul }} -
                                    {{ $book->pengarangs->first()->nama_pengarang }} 
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" 
                                   name="jumlah[]" 
                                   class="form-control jumlah-input"
                                   min="1" 
                                   value="1">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Total Pinjam</label>
                            <input type="text" class="form-control subtotal" readonly value="1">
                        </div>

                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm btn-remove w-100">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>

                    </div>
                </div>

                <div class="text-end mb-4">
                    <button type="button" id="btn-add" class="btn btn-sm btn-outline-primary">
                        <i class="bx bx-plus"></i> Tambah Buku
                    </button>
                </div>

                <!-- Tanggal -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Peminjaman</label>
                        <input type="date" name="tanggal_peminjaman" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Kembali</label>
                        <input type="date" name="tanggal_kembali" class="form-control" required>
                    </div>
                </div>

                <!-- Total Buku -->
                <div class="text-end mb-3">
                    <h5 class="fw-bold">Total Buku: <span id="total">0</span></h5>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Simpan Transaksi
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

{{-- Script JS --}}
<script>
    function hitungSubtotal() {
        let total = 0;

        document.querySelectorAll('.buku-item').forEach(item => {
            let jumlah = parseInt(item.querySelector('.jumlah-input').value || 0);
            let subtotalInput = item.querySelector('.subtotal');

            subtotalInput.value = jumlah;
            total += jumlah;
        });

        document.getElementById('total').innerText = total;
    }

    document.addEventListener('input', hitungSubtotal);

    // Tambah Buku
    document.getElementById('btn-add').addEventListener('click', function () {
        let wrapper = document.getElementById('buku-wrapper');
        let first = wrapper.querySelector('.buku-item');

        let newRow = first.cloneNode(true);

        newRow.querySelector('.buku-select').value = '';
        newRow.querySelector('.jumlah-input').value = 1;
        newRow.querySelector('.subtotal').value = 1;

        wrapper.appendChild(newRow);
        hitungSubtotal();
    });

    // Hapus Buku
    document.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove')) {
            let items = document.querySelectorAll('.buku-item');
            if (items.length > 1) {
                e.target.closest('.buku-item').remove();
                hitungSubtotal();
            }
        }
    });
</script>

@endsection