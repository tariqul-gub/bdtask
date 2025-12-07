@extends('layouts.accounting')

@section('title', 'Chart of Accounts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Chart of Accounts</h1>
    <a href="{{ route('accounts.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Create Account
    </a>
</div>

<!-- Filter -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('accounts.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by code or name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    @foreach($accountTypes as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="group_id" class="form-select">
                    <option value="">All Groups</option>
                    @foreach($accountGroups as $group)
                        <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
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
                        <th>Code</th>
                        <th>Account Name</th>
                        <th>Type</th>
                        <th>Account Group</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $account)
                        <tr>
                            <td><code>{{ $account->code }}</code></td>
                            <td>{{ $account->name }}</td>
                            <td>
                                <span class="badge 
                                    @if($account->type == 'Asset') bg-success
                                    @elseif($account->type == 'Liability') bg-danger
                                    @elseif($account->type == 'Equity') bg-primary
                                    @elseif($account->type == 'Revenue') bg-info
                                    @else bg-warning
                                    @endif">
                                    {{ $account->type }}
                                </span>
                            </td>
                            <td>{{ $account->accountGroup->name }}</td>
                            <td class="text-end">
                                <a href="{{ route('accounts.show', $account) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="{{ route('accounts.edit', $account) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="d-inline">
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
                            <td colspan="5" class="text-center text-muted py-4">No accounts found. Create your first account!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $accounts->links() }}
</div>
@endsection
