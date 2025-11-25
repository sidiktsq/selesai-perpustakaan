@extends('layouts.dahsboard')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    Ini Diaa!!!
                    <a href="{{ route('pengarang.index') }}" class="btn btn-sm btn-outline-primary">Kembali</a>
                </div>

                <div class="card-body">

                    <h4 class="fw-bold">Pengarang</h4>
                    <p><strong>Nama Pengarang :</strong> {{ $pengarangs->nama_pengarang }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection