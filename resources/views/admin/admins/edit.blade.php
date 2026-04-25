@extends('dashboard.layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        {{-- Page Header --}}
        <div class="page-title mb-5 d-flex justify-content-between align-items-center">
            <div>
                {{-- Dynamic Title based on type --}}
                @php
                    $roleLabel = 'Administrator';
                    if ($type === 'agent') { $roleLabel = 'Support Agent'; }
                    elseif ($admin->role === 'finance_admin') { $roleLabel = 'Finance Admin'; }
                @endphp
                <h1 class="font-weight-bold text-dark">Edit {{ $roleLabel }}</h1>
                <p class="text-muted">Updating access for: <strong>{{ $admin->email }}</strong></p>
            </div>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-light shadow-sm" style="border-radius: 10px;">
                <i class="fas fa-arrow-left mr-2"></i> BACK
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <div class="card-body">
                        <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            {{-- IMPORTANT: Pass the type so the Controller knows which table to update --}}
                            <input type="hidden" name="type" value="{{ $type }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-dark font-weight-bold">Email Address</label>
                                        <input type="email" name="email" class="form-control" 
                                               value="{{ $admin->email }}" required style="border-radius: 8px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-dark font-weight-bold">New Password</label>
                                        <input type="password" name="password" class="form-control" 
                                               placeholder="Leave blank to keep current" style="border-radius: 8px;">
                                    </div>
                                </div>
                            </div>

                            {{-- Only show Role selection if the user is NOT an agent --}}
                            @if($type !== 'agent')
                                <div class="form-group mb-4">
                                    <label class="text-dark font-weight-bold">Access Level / Role</label>
                                    <select name="role" class="form-control" style="border-radius: 8px;">
                                        <option value="event_admin" {{ $admin->role == 'event_admin' ? 'selected' : '' }}>
                                            Event Admin (Restricted)
                                        </option>
                                        <option value="finance_admin" {{ $admin->role == 'finance_admin' ? 'selected' : '' }}>
                                            Finance Admin (Revenue & Payments)
                                        </option>
                                        <option value="super_admin" {{ $admin->role == 'super_admin' ? 'selected' : '' }}>
                                            Super Admin (Full Access)
                                        </option>
                                        <option value="supporter" {{ $admin->role == 'supporter' ? 'selected' : '' }}>
                                            Supporter (Ticket Printing Access)
                                        </option>
                                    </select>
                                </div>
                            @else
                                {{-- Hidden input for agent to satisfy the 'role' validation if your controller requires it --}}
                                <input type="hidden" name="role" value="agent">
                                <div class="alert alert-light border-0" style="background: #fff9e6; border-left: 4px solid #ffbc11;">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1 text-warning"></i> 
                                        Support Agents have fixed access permissions for the scanning portal.
                                    </small>
                                </div>
                            @endif

                            <hr class="my-4">

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary px-5" style="border-radius: 10px; font-weight: 700;">
                                    UPDATE SETTINGS
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