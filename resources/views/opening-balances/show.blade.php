@extends('layouts.accounting')

@section('title', 'Opening Balance Details')

@section('content')
<div class="mb-4">
    <h1 class="h2">Opening Balance Details</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('opening-balances.index') }}">Opening Balances</a></li>
            <li class="breadcrumb-item active">Details</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Opening Balance Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">ID:</th>
                        <td><span class="badge bg-secondary">{{ $openingBalance->id }}</span></td>
                    </tr>
                    <tr>
                        <th>Branch:</th>
                        <td><i class="bi bi-building text-primary"></i> {{ $openingBalance->branch->name }}</td>
                    </tr>
                    <tr>
                        <th>Account Code:</th>
                        <td><code class="fs-5">{{ $openingBalance->account->code }}</code></td>
                    </tr>
                    <tr>
                        <th>Account Name:</th>
                        <td><strong>{{ $openingBalance->account->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Account Type:</th>
                        <td>
                            <span class="badge 
                                @if($openingBalance->account->type == 'Asset') bg-success
                                @elseif($openingBalance->account->type == 'Liability') bg-danger
                                @elseif($openingBalance->account->type == 'Equity') bg-primary
                                @elseif($openingBalance->account->type == 'Revenue') bg-info
                                @else bg-warning
                                @endif">
                                {{ $openingBalance->account->type }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Account Group:</th>
                        <td>{{ $openingBalance->account->accountGroup->name }}</td>
                    </tr>
                    <tr>
                        <th>Date:</th>
                        <td>{{ $openingBalance->date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Debit Amount:</th>
                        <td>
                            @if($openingBalance->debit > 0)
                                <strong class="text-success fs-5">{{ number_format($openingBalance->debit, 2) }}</strong>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Credit Amount:</th>
                        <td>
                            @if($openingBalance->credit > 0)
                                <strong class="text-danger fs-5">{{ number_format($openingBalance->credit, 2) }}</strong>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created At:</th>
                        <td>{{ $openingBalance->created_at->format('M d, Y H:i A') }}</td>
                    </tr>
                </table>

                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('opening-balances.edit', $openingBalance) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('opening-balances.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                    <form action="{{ route('opening-balances.destroy', $openingBalance) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
