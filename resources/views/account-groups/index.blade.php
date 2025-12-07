@extends('layouts.accounting')

@section('title', 'Account Groups')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Account Groups</h1>
    <a href="{{ route('account-groups.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Create Account Group
    </a>
</div>

<!-- Filter -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('account-groups.index') }}" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="parent_id" class="form-select">
                    <option value="">All Groups</option>
                    <option value="root" {{ request('parent_id') == 'root' ? 'selected' : '' }}>Root Groups Only</option>
                    @foreach($parentGroups as $group)
                        <option value="{{ $group->id }}" {{ request('parent_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="{{ route('account-groups.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-rotate"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Parent Group</th>
                        <th>Accounts</th>
                        <th>Created At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accountGroups as $group)
                        <tr>
                            <td>{{ $group->id }}</td>
                            <td>
                                <i class="bi bi-folder2 text-warning me-1"></i>
                                {{ $group->name }}
                            </td>
                            <td>
                                @if($group->parent)
                                    <span class="badge bg-secondary">{{ $group->parent->name }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $group->accounts_count ?? $group->accounts->count() }}</span>
                            </td>
                            <td>{{ $group->created_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('account-groups.show', $group) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="{{ route('account-groups.edit', $group) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('account-groups.destroy', $group) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No account groups found. Create your first account group!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(method_exists($accountGroups, 'links'))
<div class="mt-3">
    {{ $accountGroups->links() }}
</div>
@endif
@endsection
