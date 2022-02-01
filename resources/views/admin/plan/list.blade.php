@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
       <table class="table table-stripped">
            <thead>
                <tr>
                    <th>Plan ID</th>
                    <th>Plan Name</th>
                    <th>Amount</th>
                    <th>Interval</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plans as $plan)
                    <tr>
                        <td>{{ $plan->stripe_id }}</td>
                        <td>{{ $plan->name }}</td>
                        <td>{{ $plan->amount }}</td>
                        <td>{{ $plan->interval }}</td>
                        <td>
                            <form action="{{ route('admin.plan.destroy', ['plan' => $plan->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
       </table>
    </div>
</div>
@endsection
