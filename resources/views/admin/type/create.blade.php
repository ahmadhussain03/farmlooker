@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <form class="form" method="POST" action="{{ route('admin.type.store') }}">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="type">Animal Type</label>
                    <input type="text" name="type" placeholder="Enter Animal Type..." class="form-control @error('type') is-invalid @enderror">
                    @error('type')
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
