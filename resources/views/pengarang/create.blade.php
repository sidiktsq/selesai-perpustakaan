@extends('layouts.dahsboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="float-start">
                        Tambah Pengarang
                    </div>
                    <div class="float-end">
                        <a href="{{ route('pengarang.index') }}" class="btn btn-sm btn-outline-primary">Kembali</a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('pengarang.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Pengarang</label>
                            <input type="text" class="form-control @error('nama_pengarang') is-invalid @enderror" name="nama_pengarang" value="{{ old('nama_pengarang') }}" placeholder="Nama Pengarang" required>
                            @error('nama_pengarang')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                        <button type="reset" class="btn btn-sm btn-warning">Reset</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection