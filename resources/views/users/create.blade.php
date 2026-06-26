@extends('layouts.app')

@section('title', 'Register New User')
@section('page_title', 'Register New User/Admin')

@section('content')
<div class="card max-w-lg mx-auto" style="max-width: 600px;">
    <div class="card-header bg-transparent">
        <span class="fs-5 fw-bold">User Details</span>
    </div>
    <form action="{{ route('users.store') }}" method="POST" class="card-body">
        @csrf
        <div class="mb-3">
            <label class="form-label">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email Address <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Role <span class="text-danger">*</span></label>
            <select name="role" class="form-select" required>
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Normal User</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Password <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control" required minlength="6">
        </div>
        <div class="mb-4">
            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
            <input type="password" name="password_confirmation" class="form-control" required minlength="6">
        </div>
        <div class="d-flex justify-content-end gap-2 border-top pt-3">
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Register User</button>
        </div>
    </form>
</div>
@endsection
