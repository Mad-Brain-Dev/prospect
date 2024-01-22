@extends('layouts.master')

@section('content')
    <div class="mb-time-container">
        <create-time-share-component :auth_type="{{json_encode($auth->user_type)}}"/>
    </div>
@endsection

@push('script')
@endpush

