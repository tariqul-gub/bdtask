@extends('layouts.accounting')

@section('title', 'Journal Entries')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Journal Entries</h1>
    <a href="{{ route('journal-entries.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Create Journal Entry
    </a>
</div>

<!-- Filter -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('journal-entries.index') }}" class="row g-3">
            <div class="col-md-2">
                <input type="text" name="search" class="form-control" placeholder="Search description..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="branch_id" class="form-select">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="from_date" class="form-control" placeholder="From Date" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="to_date" class="form-control" placeholder="To Date" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="{{ route('journal-entries.index') }}" class="btn btn-secondary">
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
                        <th>Date</th>
                        <th>Branch</th>
                        <th>Description</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($journalEntries as $entry)
                        <tr>
                            <td>{{ $entry->id }}</td>
                            <td>{{ $entry->entry_date->format('M d, Y') }}</td>
                            <td>{{ $entry->branch->name }}</td>
                            <td>{{ Str::limit($entry->description, 50) }}</td>
                            <td><span class="badge bg-secondary">{{ $entry->source_module }}</span></td>
                            <td>
                                <span class="badge 
                                    @if($entry->status == 'Approved') bg-success
                                    @elseif($entry->status == 'Pending') bg-warning
                                    @else bg-secondary
                                    @endif">
                                    {{ $entry->status }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('journal-entries.show', $entry) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                @if($entry->status !== 'Approved')
                                    <a href="{{ route('journal-entries.edit', $entry) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No journal entries found. Create your first entry!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $journalEntries->links() }}
</div>
@endsection
