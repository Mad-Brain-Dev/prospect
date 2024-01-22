@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <find-customer-component :auth_type="{{json_encode($auth->user_type)}}"/>
        </div>

    </div>
@endsection

@push('script')
    <script src="{{ asset('/admin/js/passwordCheck.js') }}"></script>
@endpush

