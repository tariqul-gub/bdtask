@extends('layouts.accounting')

@section('title', 'Account Details')

@section('content')
<div class="mb-4">
    <h1 class="h2">Account Details</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('accounts.index') }}">Chart of Accounts</a></li>
            <li class="breadcrumb-item active">{{ $account->code }}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Account Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Account Code:</th>
                        <td><code class="fs-5">{{ $account->code }}</code></td>
                    </tr>
                    <tr>
                        <th>Account Name:</th>
                        <td><strong>{{ $account->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Type:</th>
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
                    </tr>
                    <tr>
                        <th>Account Group:</th>
                        <td>
                            <a href="{{ route('account-groups.show', $account->accountGroup) }}">
                                {{ $account->accountGroup->name }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Created At:</th>
                        <td>{{ $account->created_at->format('M d, Y H:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At:</th>
                        <td>{{ $account->updated_at->format('M d, Y H:i A') }}</td>
                    </tr>
                </table>

                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('accounts.edit', $account) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                    <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="d-inline">
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
