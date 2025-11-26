@extends('layouts.dahsboard')

@section('content')
    <div class="card mt-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="card-header">Daftar Buku</h5>
            <a href="{{ route('buku.create') }}" class="btn btn-primary ">Tambah Data</a>

        </div>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Pengarang</th>
                        <th>Stok</th>
                        <th>Tahun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bukus as $index => $buku)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-semibold">{{ $buku->judul }}</td>
                            <td>{{ $buku->kategori->nama_kategori ?? '-' }}</td>
                            <td>
                                @if ($buku->pengarangs->isNotEmpty())
                                    @foreach ($buku->pengarangs as $pengarang)
                                        {{ $pengarang->nama_pengarang }}<br>
                                    @endforeach
                                @else
                                    <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>
                            <td>{{ $buku->stok }}</td>
                            <td>{{ $buku->tahun }}</td>
                            <td>
                                <form action="{{ route('buku.destroy', $buku->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{ route('buku.show', $buku->id) }}"
                                                class="btn btn-sm btn-primary">Show</a> |
                                            <a href="{{ route('buku.edit', $buku->id) }}"
                                                class="btn btn-sm btn-success">Edit</a> |
                                            <button type="submit" onsubmit="return confirm('Are You Sure ?');"
                                                class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted text-center py-4">
                                Tidak ada data buku.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


@endsection