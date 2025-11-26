@extends('layouts.dahsboard')

@section('content')
<div class="container-fluid">

    <!-- Title + Button -->
    <div class="row mb-4">
        <div class="col">
            <h5 class="fw-bold">Data Transaksi</h5>
        </div>
        <div class="col text-end">
            <a href="{{ route('peminjaman.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus"></i> Tambah Data
            </a>
        </div>
    </div>

    <!-- Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('peminjaman.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari kode transaksi..." value="{{ $search ?? '' }}">
                    <button class="btn btn-primary" type="submit">Cari</button>

                    @if(!empty($search))
                        <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div> {{-- <-- CARD SEARCH TUTUP --}}

    <!-- Alert Success -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Table -->
    <div class="card">
        <div class="card-body">

            @if ($peminjaman->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Peminjaman</th>
                                <th>Nama Peminjam</th>
                                <th>Kategori Buku</th>
                                <th>Judul Buku</th>
                                <th>Pengarang</th>
                                <th>Tanggal Peminjaman</th>
                                <th>Tanggal Kembali</th>
                                <th>Jumlah</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($peminjaman as $no => $pj)
                                <tr>
                                    <td>{{ $no + 1 }}</td>

                                    <td class="fw-semibold text-primary">
                                        {{ $pj->kode_peminjaman }}
                                    </td>

                                    <td>{{ $pj->nama_peminjam }}</td>

                                    <td>
                                        @foreach($pj->bukus as $buku)
                                            <span class="badge bg-info mb-1">
                                                {{ $buku->kategori->nama_kategori ?? '-' }}
                                            </span><br>
                                        @endforeach
                                    </td>

                                    <td>
                                        @foreach ($pj->bukus as $buku)
                                            {{ $buku->judul }}<br>
                                        @endforeach
                                    </td>

                                    <td>
                                        @foreach ($pj->bukus as $buku)
                                            {{ $buku->pengarangs->pluck('nama_pengarang')->join(', ') ?: '-' }}
                                            <br>
                                        @endforeach
                                    </td>

                                    <td>{{ \Carbon\Carbon::parse($pj->tanggal_peminjaman)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pj->tanggal_kembali)->format('d/m/Y') }}</td>

                                    <td class="fw-bold text-center">
                                        {{ $pj->bukus->sum('pivot.jumlah') }}
                                    </td>

                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('peminjaman.show', $pj->id) }}">
                                                    <i class="bx bx-show me-2"></i> Show
                                                </a>
                                                <a class="dropdown-item" href="{{ route('peminjaman.edit', $pj->id) }}">
                                                    <i class="bx bx-edit-alt me-2"></i> Edit
                                                </a>
                                                <form action="{{ route('peminjaman.destroy', $pj->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bx bx-trash me-2"></i> Delete
                                                </button>
                                            </form>

                                                </a>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center mb-0">
                    Data Transaksi belum tersedia.
                </div>
            @endif

        </div>
    </div> {{-- end card table --}}

</div>
@endsection
