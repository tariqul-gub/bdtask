@extends('layouts.accounting')

@section('title', 'User Details')

@section('content')
<div class="mb-4">
    <h1 class="h2">User Details</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
            <li class="breadcrumb-item active">{{ $user->name }}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-person-circle"></i> User Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">ID:</th>
                        <td><span class="badge bg-secondary">{{ $user->id }}</span></td>
                    </tr>
                    <tr>
                        <th>Name:</th>
                        <td>
                            <strong class="fs-5">{{ $user->name }}</strong>
                            @if($user->id === auth()->id())
                                <span class="badge bg-success ms-2">Current User</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($user->email_verified_at)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Verified
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="bi bi-clock"></i> Pending Verification
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Joined:</th>
                        <td>{{ $user->created_at->format('M d, Y H:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated:</th>
                        <td>{{ $user->updated_at->format('M d, Y H:i A') }}</td>
                    </tr>
                </table>

                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                    @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
