@extends('dashboard.layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-title mb-5 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="font-weight-bold text-dark">Team Management</h1>
                <p class="text-muted">High-level access control for your system.</p>
            </div>
            <a href="{{ route('admin.admins.create') }}" class="btn btn-success shadow-sm" style="border-radius: 10px; font-weight: 700;">
                <i class="fas fa-user-plus mr-2"></i> ADD NEW ADMIN
            </a>
        </div>

        {{-- Section 1: Super Admins --}}
        <h3 class="font-weight-bold text-primary mb-4"><i class="fas fa-crown mr-2"></i> Super Administrators</h3>
        <div class="row mb-5">
            @foreach($superAdmins as $admin)
                <div class="col-xl-4 col-md-6 mb-4">
                    @include('admin.admins.partials.admin-card', ['admin' => $admin, 'type' => 'super'])
                </div>
            @endforeach
        </div>

        <hr class="my-5">

        {{-- Section 2: Event Admins --}}
        <h3 class="font-weight-bold text-info mb-4"><i class="fas fa-user-cog mr-2"></i> Event Administrators</h3>
        <div class="row">
            @forelse($eventAdmins as $admin)
                <div class="col-xl-4 col-md-6 mb-4">
                    @include('admin.admins.partials.admin-card', ['admin' => $admin, 'type' => 'event'])
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted">No operational admins assigned yet.</p>
                </div>
            @endforelse
        </div>

        <h3 class="font-weight-bold text-warning mb-4">
            <i class="fas fa-user-shield mr-2"></i> Support Agents
        </h3>
        <div class="row">
            @forelse($agents as $agent)
                <div class="col-xl-4 col-md-6 mb-4">
                    {{-- We pass $agent into the 'admin' variable slot for the partial --}}
                    @include('admin.admins.partials.admin-card', ['admin' => $agent, 'type' => 'agent'])
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted">No support agents created yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection