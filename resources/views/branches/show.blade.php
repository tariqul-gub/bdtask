@extends('layouts.accounting')

@section('title', 'Branch Details')

@section('content')
<div class="mb-4">
    <h1 class="h2">Branch Details</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('branches.index') }}">Branches</a></li>
            <li class="breadcrumb-item active">{{ $branch->name }}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Branch Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">ID:</th>
                        <td>{{ $branch->id }}</td>
                    </tr>
                    <tr>
                        <th>Name:</th>
                        <td>{{ $branch->name }}</td>
                    </tr>
                    <tr>
                        <th>Created At:</th>
                        <td>{{ $branch->created_at->format('M d, Y H:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At:</th>
                        <td>{{ $branch->updated_at->format('M d, Y H:i A') }}</td>
                    </tr>
                </table>

                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('branches.edit', $branch) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('branches.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                    <form action="{{ route('branches.destroy', $branch) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
