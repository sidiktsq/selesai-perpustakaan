@extends('layouts.dahsboard')

@section('content')
<div class="container">
    <h3 class="mb-4">Edit Transaksi</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('peminjaman.update', $peminjaman->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" class="form-control"
                        value="{{ old('nama_peminjam', $peminjaman->nama_peminjam) }}" required>
                </div>

                <hr>
                <h5>Daftar Buku</h5>

                <div id="buku-wrapper">
                    {{-- LOOP DATA BUKU DARI PEMINJAMAN --}}
                    @foreach ($peminjaman->bukus as $pb)
                    <div class="row buku-item mb-3">
                        <div class="col-md-5">
                            <label class="form-label">Buku</label>
                            <select name="buku_id[]" class="form-select buku-select" required>
                                <option value="">-- Pilih Buku --</option>
                                @foreach ($buku as $book)
                                    <option value="{{ $book->id }}"
                                        {{ $book->id == $pb->id ? 'selected' : '' }}>
                                        {{ $book->kategoriBuku->nama_kategori ?? '-' }} -
                                        {{ $book->pengarangs->pluck('nama_pengarang')->join(', ') }} -
                                        {{ $book->judul }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah[]" class="form-control jumlah-input"
                                min="1" value="{{ old('jumlah.' . $loop->index, $pb->pivot->jumlah) }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Total Pinjam</label>
                            <input type="text" class="form-control subtotal" readonly
                                value="{{ old('jumlah.' . $loop->index, $pb->pivot->jumlah) }}">
                        </div>

                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-remove w-100">×</button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="text-end mb-3">
                    <button type="button" class="btn btn-sm btn-secondary" id="btn-add">+ Tambah Buku</button>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_peminjaman" class="form-control"
                            value="{{ old('tanggal_peminjaman', \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->toDateString()) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Kembali</label>
                        <input type="date" name="tanggal_kembali" class="form-control"
                            value="{{ old('tanggal_kembali', \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->toDateString()) }}" required>
                    </div>
                </div>

                <div class="text-end mb-4">
                    <h5>Total Buku: <span id="total">0</span></h5>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary btn-sm">Update Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script --}}
@push('scripts')
<script>
    function hitungSubtotal() {
        let total = 0;

        document.querySelectorAll('.buku-item').forEach(item => {
            let jumlahInput = item.querySelector('.jumlah-input');
            let subtotalInput = item.querySelector('.subtotal');

            let jumlah = parseInt(jumlahInput.value) || 0;

            subtotalInput.value = jumlah;
            total += jumlah;
        });

        document.getElementById('total').innerText = total;
    }

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('jumlah-input')) {
            hitungSubtotal();
        }
    });

    // === TAMBAH BUKU ===
    document.getElementById('btn-add').addEventListener('click', function () {
        let wrapper = document.getElementById('buku-wrapper');
        let first = wrapper.querySelector('.buku-item');

        // cloneNode(true) = clone semua isi
        let newRow = first.cloneNode(true);

        // Reset SELECT
        newRow.querySelector('.buku-select').selectedIndex = 0;

        // Reset jumlah
        newRow.querySelector('.jumlah-input').value = 1;

        // Reset subtotal
        newRow.querySelector('.subtotal').value = 1;

        wrapper.appendChild(newRow);

        hitungSubtotal();
    });

    // === HAPUS BUKU ===
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove')) {
            let items = document.querySelectorAll('.buku-item');

            // minimal harus ada 1 baris
            if (items.length > 1) {
                e.target.closest('.buku-item').remove();
                hitungSubtotal();
            }
        }
    });

    // Hitung total awal
    hitungSubtotal();
</script>
@endpush

@endsection