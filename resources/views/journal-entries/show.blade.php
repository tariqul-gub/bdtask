@extends('layouts.accounting')

@section('title', 'Journal Entry Details')

@section('content')
<div class="mb-4">
    <h1 class="h2">Journal Entry #{{ $journalEntry->id }}</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('journal-entries.index') }}">Journal Entries</a></li>
            <li class="breadcrumb-item active">Entry #{{ $journalEntry->id }}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Entry Information</h5>
                <span class="badge 
                    @if($journalEntry->status == 'Approved') bg-success
                    @elseif($journalEntry->status == 'Pending') bg-warning text-dark
                    @else bg-secondary
                    @endif fs-6">
                    {{ $journalEntry->status }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Entry ID:</th>
                                <td>{{ $journalEntry->id }}</td>
                            </tr>
                            <tr>
                                <th>Branch:</th>
                                <td>{{ $journalEntry->branch->name }}</td>
                            </tr>
                            <tr>
                                <th>Entry Date:</th>
                                <td>{{ $journalEntry->entry_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Source Module:</th>
                                <td><span class="badge bg-secondary">{{ $journalEntry->source_module }}</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Status:</th>
                                <td>
                                    <span class="badge 
                                        @if($journalEntry->status == 'Approved') bg-success
                                        @elseif($journalEntry->status == 'Pending') bg-warning text-dark
                                        @else bg-secondary
                                        @endif">
                                        {{ $journalEntry->status }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created At:</th>
                                <td>{{ $journalEntry->created_at->format('M d, Y H:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At:</th>
                                <td>{{ $journalEntry->updated_at->format('M d, Y H:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6 class="fw-bold">Description:</h6>
                        <p class="text-muted">{{ $journalEntry->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Journal Lines</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Account Code</th>
                                <th>Account Name</th>
                                <th>Account Group</th>
                                <th class="text-end">Debit</th>
                                <th class="text-end">Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($journalEntry->journalLines as $line)
                                <tr>
                                    <td><code>{{ $line->account->code }}</code></td>
                                    <td>{{ $line->account->name }}</td>
                                    <td>{{ $line->account->accountGroup->name }}</td>
                                    <td class="text-end">
                                        @if($line->debit > 0)
                                            <strong>{{ number_format($line->debit, 2) }}</strong>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($line->credit > 0)
                                            <strong>{{ number_format($line->credit, 2) }}</strong>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end">Totals:</th>
                                <th class="text-end">{{ number_format($journalEntry->total_debit, 2) }}</th>
                                <th class="text-end">{{ number_format($journalEntry->total_credit, 2) }}</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-end">Difference:</th>
                                <th colspan="2" class="text-center">
                                    @php
                                        $diff = abs($journalEntry->total_debit - $journalEntry->total_credit);
                                    @endphp
                                    <span class="badge {{ $diff < 0.01 ? 'bg-success' : 'bg-danger' }}">
                                        {{ number_format($diff, 2) }}
                                    </span>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            @if($journalEntry->status !== 'Approved')
                <form action="{{ route('journal-entries.approve', $journalEntry) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this entry?')">
                        <i class="bi bi-check-circle"></i> Approve Entry
                    </button>
                </form>
                <a href="{{ route('journal-entries.edit', $journalEntry) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('journal-entries.destroy', $journalEntry) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            @else
                <div class="alert alert-success mb-0">
                    <i class="bi bi-check-circle-fill"></i> This entry has been approved and is now locked.
                </div>
            @endif
            <a href="{{ route('journal-entries.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
</div>
@endsection
