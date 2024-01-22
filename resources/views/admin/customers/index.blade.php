@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
{{--                        <div class="col-md-5">--}}
{{--                            <h4 class="card-title mb-3 text-white">{{get_page_meta()}}</h4>--}}
{{--                        </div>--}}
                        <div class="col-md-12">
                            <div class="buttons d-flex justify-content-between">
                                <h class="card-title" style="margin-right: 15px;">Total Prospect : {{$total_prospect}}</h>
                                <div class="d-flex justify-content-end">
                                    <a href="{{route('admin.customers.findInRadiusGet')}}" class="btn btn-sm btn-success text-capitalize" style="padding-top: 8px; margin-right: 15px;">Find Prospects In Radius</a>
                                    {{--                            <a href="{{route('admin.customers.create')}}" class="btn btn-sm btn-primary text-capitalize" style="padding-top: 8px;">Create Prospect</a>--}}
                                    <a href="{{route('admin.customers.downloadSampleFile')}}" class="btn btn-sm btn-secondary text-capitalize" style="padding-top: 8px; margin-right: 15px;">Download Sample File</a>
                                    <excel-upload-modal-component/>
                                </div>
                            </div>
                        </div>
                    </div>
{{--                    {!! $dataTable->table(['class'=>'table-responsive']) !!}--}}
                </div>
            </div>

        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-md-5">
                                <h4 class="card-title">Timeshare</h4>
                            </div>
                            <div class="col-md-7 text-end">
                                <a href="{{route('admin.times.create')}}" class="btn btn-sm btn-primary text-capitalize" style="padding-top: 8px;">Create Timeshare</a>
                            </div>
                        </div>
                    </div>
                    <div class="suppression_div">
                        {!! $dataTable->table(['class'=>'table-responsive']) !!}
{{--                        <suppression-component/>--}}
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('style')
    @include('includes.styles.datatable')
@endpush

@push('script')
    @include('includes.scripts.datatable')
@endpush
