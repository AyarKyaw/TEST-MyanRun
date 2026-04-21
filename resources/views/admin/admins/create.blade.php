@extends('dashboard.layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-title mb-5 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="font-weight-bold text-dark">Create Team Member</h1>
                <p class="text-muted">Register a new Admin, Finance Officer, or Support Agent.</p>
            </div>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-light shadow-sm" style="border-radius: 10px; font-weight: 700;">
                <i class="fas fa-arrow-left mr-2"></i> BACK TO LIST
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <div class="card-header border-0 bg-transparent pt-4">
                        <h4 class="card-title font-weight-bold text-primary">
                            <i class="fas fa-user-plus mr-2"></i> Account Details
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-dark font-weight-bold">Email Address</label>
                                        <input type="email" name="email" class="form-control" placeholder="e.g. member@gmail.com" style="border-radius: 8px; padding: 12px;" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-dark font-weight-bold">Initial Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters" style="border-radius: 8px; padding: 12px;" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="text-dark font-weight-bold">Access Level / Role</label>
                                <select name="role" class="form-control default-select" style="border-radius: 8px; height: 50px;">
                                    <option value="event_admin">Event Admin (Dashboard Access)</option>
                                    <option value="finance_admin">Finance Admin (Revenue & Payments)</option>
                                    <option value="super_admin">Super Admin (Full Access)</option>
                                    <option value="agent">Support Agent</option>
                                </select>
                            </div>

                            <hr class="my-4">

                            <div class="text-right">
                                <button type="reset" class="btn btn-light px-4 mr-2" style="border-radius: 10px;">Reset</button>
                                <button type="submit" class="btn btn-primary px-5 shadow" style="border-radius: 10px; font-weight: 700;">
                                    CREATE ACCOUNT
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection