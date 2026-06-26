@extends('layouts.app')

@section('title', 'Edit User')
@section('page_title', 'Edit User/Admin')

@section('content')
<div class="card max-w-lg mx-auto" style="max-width: 600px;">
    <div class="card-header bg-transparent">
        <span class="fs-5 fw-bold">Update Details for {{ $user->name }}</span>
    </div>
    <form action="{{ route('users.update', $user) }}" method="POST" class="card-body">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email Address <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Role <span class="text-danger">*</span></label>
            <select name="role" class="form-select" required>
                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Normal User</option>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
        </div>
        
        <hr class="my-4">
        <h6 class="mb-3 fw-bold text-secondary">Change Password (Leave blank to keep current)</h6>
        
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-control" minlength="6">
        </div>
        <div class="mb-4">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control" minlength="6">
        </div>
        <div class="d-flex justify-content-end gap-2 border-top pt-3">
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update User</button>
        </div>
    </form>
</div>
@endsection
