@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <form class="form" method="POST" action="{{ route('admin.notification.store') }}">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="users">User</label>
                    <select class="form-control
                        @error('users')
                            is-invalid
                        @enderror" id="users" name="users">
                        <option value="all">All</option>
                        <option value="super_admin">Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="moderator">Moderator</option>
                    </select>
                    @error('users')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Message:</label>
                    <textarea name="message" class="form-control @error('message')
                        is-invalid
                    @enderror" rows="15" placeholder="Type Message...">
                    </textarea>
                    @error('message')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary mr-2">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
