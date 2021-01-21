@extends('layouts.front')
{{--@section('pagetitle') {{ config('app.name', 'Laravel') }} @endsection--}}

@section('banner')
    <div class="video pt-8 pt-lg-12 container-fluid" style="background-image: url({{ asset('images/living-room.jpg') }});">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h1 class="flex-sm-fill font-size-h1 font-weight-bold mt-2 mb-0 mb-sm-5 text-uppercase text-white">
                    @lang('commun.buy_guide')
                </h1>
            </div>
        </div>
    </div>
@endsection

@section('breadcrumbs')
    @include('layouts.inc.breadcrumbs', [
        'breadcrumbs' => [
            ['name' => 'commun.buy_guide'],
        ]
        ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-6 offset-lg-1 col-lg-5 offset-xl-2 col-xl-4 p-0 px-md-2">
                <div class="container-fluid mb-5">
                    <p>
                        @lang('commun.buy_guide_line1')
                    </p>

                    <h3 class="mb-3">
                        @lang('commun.buy_guide_line2')
                    </h3>
                    <ul>
                        <li>@lang('commun.buy_guide_line3')</li>
                        <li>@lang('commun.buy_guide_line4')</li>
                        <li>@lang('commun.buy_guide_line5')</li>
                        <li>@lang('commun.buy_guide_line6')</li>
                    </ul>
                </div>
            </div>

            <div class="col-6 col-lg-5 col-xl-4 p-0 px-md-2">
                <div class="container-fluid mb-5">
                    <iframe
                            height="315"
                            src="https://www.youtube.com/embed/Rmpxvsv0RbY"
                            frameborder="0"
                            allowfullscreen
                            style="width:100%" class="p-1"></iframe>
                </div>
            </div>

            <div class="col-12 offset-lg-1 col-lg-10 offset-xl-2 col-xl-8 p-0 px-md-2">
                <div class="container-fluid mb-5">
                    <h3 class="mb-3">
                        @lang('commun.buy_guide_line7')
                    </h3>
                    <ul>
                        <li>@lang('commun.buy_guide_line8')</li>
                        <li>@lang('commun.buy_guide_line9')</li>
                        <li>@lang('commun.buy_guide_line10')</li>
                        <li>@lang('commun.buy_guide_line11')</li>
                        <li>@lang('commun.buy_guide_line12')</li>
                        <li>@lang('commun.buy_guide_line13')</li>
                        <li>@lang('commun.buy_guide_line14')</li>
                    </ul>

                    <p class="font-weight-bold">
                        @lang('commun.buy_guide_line15')
                    </p>

                    <h3 class="mb-3">
                        @lang('commun.buy_guide_line16')
                    </h3>
                    <p>
                        @lang('commun.buy_guide_line17')
                    </p>
                    <ol>
                        <li>@lang('commun.buy_guide_line18')</li>
                        <li>@lang('commun.buy_guide_line19')</li>
                        <li>@lang('commun.buy_guide_line20')</li>
                        <li>@lang('commun.buy_guide_line21')</li>
                        <li>@lang('commun.buy_guide_line22')</li>
                        <li>@lang('commun.buy_guide_line23')</li>
                        <li>@lang('commun.buy_guide_line24')</li>
                        <li>@lang('commun.buy_guide_line25')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
@endpush
