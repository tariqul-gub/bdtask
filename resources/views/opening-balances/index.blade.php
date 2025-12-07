@extends('layouts.accounting')

@section('title', 'Opening Balances')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Opening Balances</h1>
    <a href="{{ route('opening-balances.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Create Opening Balance
    </a>
</div>

<!-- Filter -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('opening-balances.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search account..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="branch_id" class="form-select">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="account_id" class="form-select">
                    <option value="">All Accounts</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>{{ $account->code }} - {{ $account->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="{{ route('opening-balances.index') }}" class="btn btn-secondary">
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
                        <th>Branch</th>
                        <th>Account</th>
                        <th>Date</th>
                        <th class="text-end">Debit</th>
                        <th class="text-end">Credit</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($openingBalances as $balance)
                        <tr>
                            <td>{{ $balance->id }}</td>
                            <td>
                                <i class="bi bi-building text-primary me-1"></i>
                                {{ $balance->branch->name }}
                            </td>
                            <td>
                                <span class="text-muted">{{ $balance->account->code }}</span> - 
                                {{ $balance->account->name }}
                            </td>
                            <td>{{ $balance->date->format('M d, Y') }}</td>
                            <td class="text-end">
                                @if($balance->debit > 0)
                                    <span class="text-success fw-bold">{{ number_format($balance->debit, 2) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($balance->credit > 0)
                                    <span class="text-danger fw-bold">{{ number_format($balance->credit, 2) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('opening-balances.show', $balance) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="{{ route('opening-balances.edit', $balance) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('opening-balances.destroy', $balance) }}" method="POST" class="d-inline">
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
                            <td colspan="7" class="text-center text-muted py-4">No opening balances found. Create your first opening balance!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $openingBalances->links() }}
</div>
@endsection
