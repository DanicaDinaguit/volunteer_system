@extends('layouts.admin_app')

@section('title', 'Beneficiary Registration')

@section('content')
<div class="container" style="margin-top: 40px;">

    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: #d98641;">Register New Beneficiary</h2>
        <a href="{{ route('admin.event') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Event
        </a>
    </div>

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>There were some problems with your input:</strong>
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Registration Form -->
    <form action="{{ route('admin.beneficiaries.store') }}" method="POST" class="card shadow-sm p-4 mb-5 bg-white rounded">
        @csrf
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" id="first_name" value="{{ old('first_name') }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label for="middle_name" class="form-label">Middle Name</label>
                <input type="text" name="middle_name" class="form-control" id="middle_name" value="{{ old('middle_name') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" id="last_name" value="{{ old('last_name') }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="purok" class="form-label">Purok</label>
                <select name="purok" id="purok" class="form-select" required>
                    <option value="" disabled selected>Select Partner</option>
                    @foreach($partners as $partner)
                        <option value="{{ $partner->partner_name }}">{{ $partner->partner_name }}</option>
                    @endforeach
                </select>
                        
            </div>

            <div class="col-md-6 mb-3">
                <label for="birthdate" class="form-label">Birthdate</label>
                <input type="date" name="birthdate" class="form-control" id="birthdate" value="{{ old('birthdate') }}" required>
            </div>
        </div>

        <button type="submit" class="btn w-100 mt-3" style="background-color: #d98641; color: white;">Register Beneficiary</button>
    </form>

    <!-- Beneficiary List -->
    <div id="beneficiary-list" class="mt-5">
        <h3 class="text-secondary">List of Registered Beneficiaries</h3>
        <a href="{{ route('admin.downloadListBeneficiary') }}" class="btn btn-outline-success btn-sm">
            <i class="fas fa-file-pdf me-1"></i> Export
        </a>
        <div class="table-responsive">
            <table class="table table-bordered table-hover mt-3">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Purok</th>
                        <th>Birthdate</th>
                        <th>Registered At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beneficiaries as $beneficiary)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $beneficiary->id }}</td>
                            <td>{{ $beneficiary->first_name }} {{ $beneficiary->middle_name }} {{ $beneficiary->last_name }}</td>
                            <td>{{ $beneficiary->purok }}</td>
                            <td>{{ \Carbon\Carbon::parse($beneficiary->birthdate)->format('F j, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($beneficiary->created_at)->format('F j, Y') }}</td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No beneficiaries registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
