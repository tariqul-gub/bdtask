@extends('layouts.accounting')

@section('title', 'Chart of Accounts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Chart of Accounts</h1>
    <a href="{{ route('accounts.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create Account
    </a>
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
