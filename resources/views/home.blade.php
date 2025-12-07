@extends('layouts.accounting')

@section('title', 'Dashboard')

@section('content')
<style>
    .stat-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .stat-card.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .stat-card.orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .stat-card.blue { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
    .stat-card.purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .stat-card .stat-icon {
        font-size: 2.5rem;
        opacity: 0.3;
        position: absolute;
        right: 1rem;
        top: 1rem;
    }
    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
    }
    .stat-card .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    .quick-action {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-radius: 12px;
        border: 2px solid var(--border-color);
        text-decoration: none;
        color: var(--text-primary);
        transition: all 0.2s ease;
    }
    .quick-action:hover {
        border-color: var(--primary-color);
        background: rgba(79, 70, 229, 0.05);
        transform: translateX(5px);
    }
    .quick-action .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
    }
    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
    }
    .feature-item:last-child { border-bottom: none; }
    .feature-item .check-icon {
        width: 24px;
        height: 24px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.75rem;
    }
</style>

<div class="mb-4">
    <h1 class="h2">Dashboard</h1>
    <p class="text-muted">Welcome back! Here's an overview of your accounting system.</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <i class="bi bi-building stat-icon"></i>
            <div class="stat-value">{{ \App\Models\Branch::count() }}</div>
            <div class="stat-label">Total Branches</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card green">
            <i class="bi bi-list-columns stat-icon"></i>
            <div class="stat-value">{{ \App\Models\Account::count() }}</div>
            <div class="stat-label">Chart of Accounts</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card orange">
            <i class="bi bi-journal-text stat-icon"></i>
            <div class="stat-value">{{ \App\Models\JournalEntry::count() }}</div>
            <div class="stat-label">Journal Entries</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card blue">
            <i class="bi bi-people stat-icon"></i>
            <div class="stat-value">{{ \App\Models\User::count() }}</div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Quick Actions -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning-charge me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    <a href="{{ route('journal-entries.create') }}" class="quick-action">
                        <div class="icon-box" style="background: linear-gradient(135deg, #4f46e5, #4338ca);">
                            <i class="bi bi-plus-lg"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Create Journal Entry</div>
                            <small class="text-muted">Record new transactions</small>
                        </div>
                    </a>
                    <a href="{{ route('accounts.create') }}" class="quick-action">
                        <div class="icon-box" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="bi bi-plus-lg"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Add New Account</div>
                            <small class="text-muted">Expand chart of accounts</small>
                        </div>
                    </a>
                    <a href="{{ route('opening-balances.create') }}" class="quick-action">
                        <div class="icon-box" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Opening Balance</div>
                            <small class="text-muted">Set initial balances</small>
                        </div>
                    </a>
                    <a href="{{ route('reports.index') }}" class="quick-action">
                        <div class="icon-box" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
                            <i class="bi bi-bar-chart"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Financial Reports</div>
                            <small class="text-muted">View consolidated reports</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- System Features -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-gear me-2"></i>System Features</h5>
            </div>
            <div class="card-body">
                <div class="feature-item">
                    <span class="check-icon"><i class="bi bi-check"></i></span>
                    <span>Centralized Chart of Accounts</span>
                </div>
                <div class="feature-item">
                    <span class="check-icon"><i class="bi bi-check"></i></span>
                    <span>Multi-Level Account Grouping</span>
                </div>
                <div class="feature-item">
                    <span class="check-icon"><i class="bi bi-check"></i></span>
                    <span>Opening Balance Management</span>
                </div>
                <div class="feature-item">
                    <span class="check-icon"><i class="bi bi-check"></i></span>
                    <span>Journal Entry Approvals</span>
                </div>
                <div class="feature-item">
                    <span class="check-icon"><i class="bi bi-check"></i></span>
                    <span>Recurring Journal Entries</span>
                </div>
                <div class="feature-item">
                    <span class="check-icon"><i class="bi bi-check"></i></span>
                    <span>Auto-Ledger Posting</span>
                </div>
                <div class="feature-item">
                    <span class="check-icon"><i class="bi bi-check"></i></span>
                    <span>Multi-Branch Ledger Structure</span>
                </div>
                <div class="feature-item">
                    <span class="check-icon"><i class="bi bi-check"></i></span>
                    <span>Consolidated Financial Views</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Journal Entries -->
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Journal Entries</h5>
        <a href="{{ route('journal-entries.index') }}" class="btn btn-sm btn-primary">View All</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Branch</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
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
                            <td>
                                <i class="bi bi-building text-primary me-1"></i>
                                {{ $entry->branch->name }}
                            </td>
                            <td>{{ Str::limit($entry->description, 40) }}</td>
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
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No journal entries yet. Create your first entry!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
