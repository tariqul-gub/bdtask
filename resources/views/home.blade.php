@extends('layouts.accounting')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h1 class="h2">Dashboard</h1>
    <p class="text-muted">Welcome to MultiBranch-Accountant - Your Complete Accounting Solution</p>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-primary">
            <div class="card-body text-center">
                <i class="bi bi-building fs-1 text-primary"></i>
                <h5 class="card-title mt-3">Branches</h5>
                <p class="card-text">{{ \App\Models\Branch::count() }}</p>
                <a href="{{ route('branches.index') }}" class="btn btn-sm btn-primary">Manage</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-success">
            <div class="card-body text-center">
                <i class="bi bi-list-ul fs-1 text-success"></i>
                <h5 class="card-title mt-3">Accounts</h5>
                <p class="card-text">{{ \App\Models\Account::count() }}</p>
                <a href="{{ route('accounts.index') }}" class="btn btn-sm btn-success">View COA</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-warning">
            <div class="card-body text-center">
                <i class="bi bi-journal-text fs-1 text-warning"></i>
                <h5 class="card-title mt-3">Journal Entries</h5>
                <p class="card-text">{{ \App\Models\JournalEntry::count() }}</p>
                <a href="{{ route('journal-entries.index') }}" class="btn btn-sm btn-warning">View All</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-info">
            <div class="card-body text-center">
                <i class="bi bi-check-circle fs-1 text-info"></i>
                <h5 class="card-title mt-3">Approved Entries</h5>
                <p class="card-text">{{ \App\Models\JournalEntry::where('status', 'Approved')->count() }}</p>
                <a href="{{ route('reports.index') }}" class="btn btn-sm btn-info">Reports</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('journal-entries.create') }}" class="btn btn-outline-primary">
                        <i class="bi bi-plus-circle"></i> Create Journal Entry
                    </a>
                    <a href="{{ route('accounts.create') }}" class="btn btn-outline-success">
                        <i class="bi bi-plus-circle"></i> Add New Account
                    </a>
                    <a href="{{ route('branches.create') }}" class="btn btn-outline-info">
                        <i class="bi bi-plus-circle"></i> Add New Branch
                    </a>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-warning">
                        <i class="bi bi-graph-up"></i> View Financial Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">System Features</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><i class="bi bi-check-circle text-success"></i> Centralized Chart of Accounts</li>
                    <li class="list-group-item"><i class="bi bi-check-circle text-success"></i> Multi-Level Account Grouping</li>
                    <li class="list-group-item"><i class="bi bi-check-circle text-success"></i> Opening Balance Management</li>
                    <li class="list-group-item"><i class="bi bi-check-circle text-success"></i> Journal Entry Approvals</li>
                    <li class="list-group-item"><i class="bi bi-check-circle text-success"></i> Recurring Entries</li>
                    <li class="list-group-item"><i class="bi bi-check-circle text-success"></i> Auto-Ledger Posting</li>
                    <li class="list-group-item"><i class="bi bi-check-circle text-success"></i> Multi-Branch Ledger</li>
                    <li class="list-group-item"><i class="bi bi-check-circle text-success"></i> Consolidated Financial Views</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Recent Journal Entries</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Branch</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $recentEntries = \App\Models\JournalEntry::with('branch')->latest()->take(5)->get();
                            @endphp
                            @forelse($recentEntries as $entry)
                                <tr>
                                    <td>{{ $entry->id }}</td>
                                    <td>{{ $entry->entry_date->format('M d, Y') }}</td>
                                    <td>{{ $entry->branch->name }}</td>
                                    <td>{{ Str::limit($entry->description, 50) }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($entry->status == 'Approved') bg-success
                                            @elseif($entry->status == 'Pending') bg-warning
                                            @else bg-secondary
                                            @endif">
                                            {{ $entry->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('journal-entries.show', $entry) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No journal entries yet. Create your first entry!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
