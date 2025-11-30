@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center text-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-5">
                    <h1 class="display-4 mb-4">Selamat Datang</h1>
                    <p class="lead">Kepada para kutu buku</p>
                    <div class="mt-4">
                        <i class="fas fa-book-reader fa-4x text-primary mb-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Create New Dashboard button has been removed as requested --}}


    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($dashboards->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Position</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dashboards as $index => $dashboard)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('dashboards.show', $dashboard->id) }}" class="text-decoration-none">
                                            {{ $dashboard->title }}
                                        </a>
                                    </td>
                                    <td>{{ Str::limit($dashboard->description, 50) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $dashboard->is_active ? 'success' : 'secondary' }}">
                                            {{ $dashboard->status_label }}
                                        </span>
                                    </td>
                                    <td>{{ $dashboard->position }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('dashboards.show', $dashboard->id) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('dashboards.edit', $dashboard->id) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('dashboards.toggle-status', $dashboard->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to {{ $dashboard->is_active ? 'deactivate' : 'activate' }} this dashboard?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="btn btn-sm {{ $dashboard->is_active ? 'btn-secondary' : 'btn-success' }}"
                                                        title="{{ $dashboard->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas {{ $dashboard->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('dashboards.destroy', $dashboard->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this dashboard?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $dashboards->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-group .btn {
        margin-right: 0.25rem;
    }
    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>
@endpush