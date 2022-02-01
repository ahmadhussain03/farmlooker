@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <form class="form" method="POST" action="{{ route('admin.plan.store') }}">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="type">Plan Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Plan Name" class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="type">Plan Description</label>
                    <input type="text" name="description" value="{{ old('description') }}" placeholder="Plan Description" class="form-control @error('description') is-invalid @enderror">
                    @error('description')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="type">Plan Pricing</label>
                    <input type="number" step="0.1" value="{{ old('amount') }}" name="amount" placeholder="Plan Pricing" class="form-control @error('pricing') is-invalid @enderror">
                    @error('pricing')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="type">Interval</label>
                    <select type="number" step="0.1" name="interval" class="form-control @error('interval') is-invalid @enderror">
                        <option value="month">Monthly</option>
                        <option value="week">Weekly</option>
                        <option value="year">Yearly</option>
                        <option value="day">Day</option>
                    </select>
                    @error('interval')
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
