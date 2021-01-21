@extends('layouts.front')
{{--@section('pagetitle') {{ config('app.name', 'Laravel') }} @endsection--}}

@section('banner')
    <div class="video pt-8 pt-lg-12 container-fluid" style="background-image: url({{ asset('images/indoor.jpg') }});">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h1 class="flex-sm-fill font-size-h1 font-weight-bold mt-2 mb-0 mb-sm-5 text-uppercase text-white">
                    @lang('commun.list_properties')
                </h1>
            </div>
        </div>
    </div>
@endsection

@section('breadcrumbs')
    @include('layouts.inc.breadcrumbs', [
        'breadcrumbs' => [
            ['name' => 'commun.list_properties'],
        ]
        ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 offset-lg-1 col-lg-10 offset-xl-2 col-xl-8 p-0 px-md-2">
                <div class="container-fluid mb-4">
                    @include('layouts.inc.search-property-form')
                </div>

                @if(isset($data))
                    <div class="container-fluid mb-3">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-between flex-wrap">
                                <h4>@lang('commun.last_search')</h4>
                                @if( !empty($data['zipcode']) )
                                    <div>
                                        <strong>@lang('commun.zipcode')</strong> {{ $data['zipcode'] }}
                                    </div>
                                @endif

                                @if( !empty($data['maxprice']) )
                                    <div>
                                        <strong>@lang('commun.maxprice')</strong> {{ $data['maxprice'] }} €
                                    </div>
                                @endif

                                @if( !empty($data['zone']) )
                                    <div>
                                        <strong>@lang('commun.zone_around')</strong> {{ $data['zone'] }} km
                                    </div>
                                @endif

                                @if( !empty($data['typeproperty']) )
                                    <div>
                                        <strong>@lang('commun.property_type')</strong> {{ __( 'commun.property_type'.$data['typeproperty']) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <div class="container-fluid mb-5">
                    @include('layouts.inc.list-properties')

                    <div class="pagination justify-content-center align-items-center text-center">
                        {{ $properties->appends(\Request::except('page'))->render() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
@endpush
