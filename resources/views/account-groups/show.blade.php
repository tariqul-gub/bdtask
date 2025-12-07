@extends('layouts.accounting')

@section('title', 'Account Group Details')

@section('content')
<div class="mb-4">
    <h1 class="h2">Account Group Details</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('account-groups.index') }}">Account Groups</a></li>
            <li class="breadcrumb-item active">{{ $accountGroup->name }}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Group Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">ID:</th>
                        <td>{{ $accountGroup->id }}</td>
                    </tr>
                    <tr>
                        <th>Name:</th>
                        <td>{{ $accountGroup->name }}</td>
                    </tr>
                    <tr>
                        <th>Parent Group:</th>
                        <td>
                            @if($accountGroup->parent)
                                <a href="{{ route('account-groups.show', $accountGroup->parent) }}">
                                    {{ $accountGroup->parent->name }}
                                </a>
                            @else
                                <span class="text-muted">Root Level</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Child Groups:</th>
                        <td>{{ $accountGroup->children->count() }}</td>
                    </tr>
                    <tr>
                        <th>Accounts:</th>
                        <td>{{ $accountGroup->accounts->count() }}</td>
                    </tr>
                </table>

                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('account-groups.edit', $accountGroup) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('account-groups.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>

        @if($accountGroup->children->count() > 0)
        <div class="card shadow-sm mb-3">
            <div class="card-header">
                <h5 class="mb-0">Child Groups</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($accountGroup->children as $child)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ route('account-groups.show', $child) }}">{{ $child->name }}</a>
                            <span class="badge bg-primary rounded-pill">{{ $child->accounts->count() }} accounts</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        @if($accountGroup->accounts->count() > 0)
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Accounts in this Group</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($accountGroup->accounts as $account)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $account->code }}</strong> - {{ $account->name }}
                            </div>
                            <span class="badge bg-info">{{ $account->type }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
