@extends('layouts.front')
{{--@section('pagetitle') {{ config('app.name', 'Laravel') }} @endsection--}}

@section('banner')
    <div class="video pt-8 pt-lg-12 container-fluid" style="background-image: url({{ asset('images/living-room.jpg') }});">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h1 class="flex-sm-fill font-size-h1 font-weight-bold mt-2 mb-0 mb-sm-5 text-uppercase text-white">
                    @lang('commun.sell_to_buy')
                </h1>
            </div>
        </div>
    </div>
@endsection

@section('breadcrumbs')
    @include('layouts.inc.breadcrumbs', [
        'breadcrumbs' => [
            ['name' => 'commun.sell_to_buy'],
        ]
        ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 offset-lg-1 col-lg-10 offset-xl-2 col-xl-8 p-0 px-md-2">
                <div class="container-fluid mb-5">
                    <p class="font-weight-bold">
                        @lang('commun.sell_to_buy_line1')
                    </p>

                    <h2 class="mb-3">
                        @lang('commun.sell_to_buy_line2')
                    </h2>
                    <p>
                        @lang('commun.sell_to_buy_line3')
                    </p>
                    <p>
                        @lang('commun.sell_to_buy_line4')
                    </p>


                    <h2 class="mb-3">
                        @lang('commun.sell_to_buy_line5')
                    </h2>
                    <p>
                        @lang('commun.sell_to_buy_line6')
                    </p>
                    <p>
                        @lang('commun.sell_to_buy_line7')
                    </p>
                    <p>
                        @lang('commun.sell_to_buy_line8')
                    </p>


                    <h2 class="mb-3">
                        @lang('commun.sell_to_buy_line9')
                    </h2>
                    <p>
                        @lang('commun.sell_to_buy_line10')
                    </p>
                    <p>
                        @lang('commun.sell_to_buy_line11')
                    </p>
                    <p>
                        @lang('commun.sell_to_buy_line12')
                    </p>
                    <p>
                        @lang('commun.sell_to_buy_line13')
                    </p>


                    <p class="font-weight-bold">
                        @lang('commun.sell_to_buy_line14')
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
@endpush
