@extends('layouts.accounting')

@section('title', 'Account Ledger')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Account Ledger</h1>
    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Back to Reports
    </a>
</div>

<!-- Filter Card -->
<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fa-solid fa-filter me-2"></i>Filter Options</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.ledger') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Account</label>
                    <select name="account_id" class="form-select" required>
                        <option value="">-- Select Account --</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ $accountId == $acc->id ? 'selected' : '' }}>
                                {{ $acc->code }} - {{ $acc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Branch</label>
                    <select name="branch_id" class="form-select">
                        <option value="consolidated">All Branches (Consolidated)</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $selectedBranch == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-magnifying-glass"></i> Generate
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($account)
<!-- Ledger Report -->
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fa-solid fa-book me-2"></i>
            Ledger: {{ $account->code }} - {{ $account->name }}
        </h5>
        <small class="text-muted">Period: {{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}</small>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Entry #</th>
                        <th>Branch</th>
                        <th>Description</th>
                        <th class="text-end">Debit</th>
                        <th class="text-end">Credit</th>
                        <th class="text-end">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-secondary">
                        <td colspan="4"><strong>Opening Balance</strong></td>
                        <td class="text-end">-</td>
                        <td class="text-end">-</td>
                        <td class="text-end"><strong>{{ number_format($openingBalance, 2) }}</strong></td>
                    </tr>
                    @php $runningBalance = $openingBalance; @endphp
                    @forelse($ledgerEntries as $entry)
                        @php
                            if (in_array($account->type, ['Asset', 'Expense'])) {
                                $runningBalance += ($entry->debit - $entry->credit);
                            } else {
                                $runningBalance += ($entry->credit - $entry->debit);
                            }
                        @endphp
                        <tr>
                            <td>{{ $entry->journalEntry->entry_date->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('journal-entries.show', $entry->journalEntry) }}">
                                    #{{ $entry->journalEntry->id }}
                                </a>
                            </td>
                            <td>{{ $entry->journalEntry->branch->name }}</td>
                            <td>{{ Str::limit($entry->journalEntry->description, 40) }}</td>
                            <td class="text-end">
                                @if($entry->debit > 0)
                                    {{ number_format($entry->debit, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end">
                                @if($entry->credit > 0)
                                    {{ number_format($entry->credit, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ number_format($runningBalance, 2) }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No transactions found for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <th colspan="4">Closing Balance</th>
                        <th class="text-end">{{ number_format($ledgerEntries->sum('debit'), 2) }}</th>
                        <th class="text-end">{{ number_format($ledgerEntries->sum('credit'), 2) }}</th>
                        <th class="text-end">{{ number_format($runningBalance ?? $openingBalance, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@else
<div class="alert alert-info">
    <i class="fa-solid fa-info-circle me-2"></i>
    Please select an account and click Generate to view the ledger.
</div>
@endif
@endsection
