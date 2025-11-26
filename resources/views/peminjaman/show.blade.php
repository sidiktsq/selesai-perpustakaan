@extends('layouts.dahsboard')

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">
                <i class="bx bx-detail me-1"></i> Detail Peminjaman
            </h4>
            <p class="text-muted small mt-1">Informasi lengkap transaksi peminjaman buku.</p>
        </div>
        <div class="col text-end">
            <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-secondary">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Card: Informasi Peminjaman -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <strong><i class="bx bx-info-circle me-1"></i> Informasi Peminjaman</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">
                    <p class="text-muted small mb-1">Kode Peminjaman</p>
                    <p class="fw-bold">{{ $peminjaman->kode_peminjaman }}</p>
                </div>

                <div class="col-md-4">
                    <p class="text-muted small mb-1">Tanggal Peminjaman</p>
                    <p class="fw-semibold">
                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d/m/Y') }}
                    </p>
                </div>

                <div class="col-md-4">
                    <p class="text-muted small mb-1">Tanggal Kembali</p>
                    <p class="fw-semibold">
                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d/m/Y') }}
                    </p>
                </div>

                <div class="col-md-4">
                    <p class="text-muted small mb-1">Jumlah Buku Dipinjam</p>
                    <span class="badge bg-primary px-3 py-2 fs-6">
                        {{ $peminjaman->bukus->sum('pivot.jumlah') }} Buku
                    </span>
                </div>

                <div class="col-md-4">
                    <p class="text-muted small mb-1">Nama Peminjam</p>
                    <p class="fw-semibold">{{ $peminjaman->nama_peminjam }}</p>
                </div>

            </div>

        </div>
    </div>

    <!-- Card: Daftar Buku -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <strong><i class="bx bx-book me-1"></i> Daftar Buku Dipinjam</strong>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Judul Buku</th>
                            <th>Pengarang</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($peminjaman->bukus as $index => $buku)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>

                                <td class="fw-semibold">{{ $buku->judul }}</td>

                                <td>
                                    <i class="bx bx-user me-1"></i>
                                    {{ $buku->pengarangs->pluck('nama_pengarang')->join(', ') ?: '-' }}
                                </td>

                                <td>
                                    <span class="badge bg-info">
                                        {{ $buku->kategori->nama_kategori ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="text-primary fw-bold ">
                                        {{ $buku->pivot->jumlah }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>
@endsection