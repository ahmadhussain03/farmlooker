@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <form class="form" method="POST" action="{{ route('admin.breed.store') }}">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="type">Animal Type</label>
                    <select class="form-control
                        @error('type')
                            is-invalid
                        @enderror" id="type" name="type">
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}">{{ $type->type }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="breed">Animal Breed</label>
                    <input type="text" name="breed" placeholder="Enter Animal Breed..." class="form-control @error('breed') is-invalid @enderror">
                    @error('breed')
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
