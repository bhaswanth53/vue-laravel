@extends('layouts.main')

@section('content')
    <div class="container">
        <h3>Customers List</h3>
        <datatable
            csrf_token="{{ csrf_token() }}"
            request_url="{{ route('requests.members') }}"
        ></datatable>
    </div>
@endsection