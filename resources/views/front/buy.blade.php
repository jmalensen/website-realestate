@extends('layouts.front')
{{--@section('pagetitle') {{ config('app.name', 'Laravel') }} @endsection--}}

@section('banner')
    <div class="video pt-8 pt-lg-12 container-fluid" style="background-image: url({{ asset('images/living-room.jpg') }});">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h1 class="flex-sm-fill font-size-h1 font-weight-bold mt-2 mb-0 mb-sm-5 text-uppercase text-white">
                    @lang('commun.buy_the')
                </h1>
            </div>
        </div>
    </div>
@endsection

@section('breadcrumbs')
    @include('layouts.inc.breadcrumbs', [
        'breadcrumbs' => [
            ['name' => 'commun.buy_the'],
        ]
        ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 offset-lg-1 col-lg-10 offset-xl-2 col-xl-8 p-0 px-md-2">
                <div class="container-fluid mb-5">
                    <h2 class="mb-3">@lang('commun.search_property')</h2>

                    @include('layouts.inc.search-property-form')
                </div>


                <div class="container-fluid mb-5">
                    <h2 class="mb-3">@lang('commun.whybuythe')</h2>

                    @php
                        $listAspects = [
                            0 => [
                                'expanded' => false,
                                'title'    => __('commun.buy_title0'),
                                'icon'     => '<i class="fa fa-2x fa-fw fa-bullseye"></i>',
                                'text'     => __('commun.buy_content0')
                            ],
                            1 => [
                                'expanded' => false,
                                'title' => __('commun.buy_title1'),
                                'icon'  => '<i class="fa fa-2x fa-fw fa-briefcase"></i>',
                                'text'  => __('commun.buy_content1')
                            ],
                            2 => [
                                'expanded' => false,
                                'title' => __('commun.buy_title2'),
                                'icon'  => '<i class="fa fa-2x fa-fw fa-mug-hot"></i>',
                                'text'  => __('commun.buy_content2')
                            ],
                            3 => [
                                'expanded' => false,
                                'title' => __('commun.buy_title3'),
                                'icon'  => '<i class="fa fa-2x fa-fw fa-user-tie"></i>',
                                'text'  => __('commun.buy_content3')
                            ],
                            4 => [
                                'expanded' => false,
                                'title' => __('commun.buy_title4'),
                                'icon'  => '<i class="fa fa-2x fa-fw fa-question"></i>',
                                'text'  => __('commun.buy_content4')
                            ],
                            5 => [
                                'expanded' => false,
                                'title' => __('commun.buy_title5'),
                                'icon'  => '<i class="fa fa-2x fa-fw fa-handshake"></i>',
                                'text'  => __('commun.buy_content5')
                            ],
                            6 => [
                                'expanded' => false,
                                'title' => __('commun.buy_title6'),
                                'icon'  => '<i class="fa fa-2x fa-fw fa-user-clock"></i>',
                                'text'  => __('commun.buy_content6')
                            ],
                            7 => [
                                'expanded' => false,
                                'title' => __('commun.buy_title7'),
                                'icon'  => '<i class="fa fa-2x fa-fw fa-road"></i>',
                                'text'  => __('commun.buy_content7')
                            ],
                            8 => [
                                'expanded' => false,
                                'title' => __('commun.buy_title8'),
                                'icon'  => '<i class="fa fa-2x fa-fw fa-comment-alt"></i>',
                                'text'  => __('commun.buy_content8')
                            ],
                            9 => [
                                'expanded' => false,
                                'title' => __('commun.buy_title9'),
                                'icon'  => '<i class="fa fa-2x fa-fw fa-lightbulb"></i>',
                                'text'  => __('commun.buy_content9')
                            ],
                            10 => [
                                'expanded' => false,
                                'title' => __('commun.buy_title10'),
                                'icon'  => '<i class="fa fa-2x fa-fw fa-moon"></i>',
                                'text'  => __('commun.buy_content10')
                            ],
                        ];
                        $i = 0;
                    @endphp
                    @include('layouts.inc.accordion')
                </div>


                <div class="container-fluid mb-5">
                    <h2 class="mb-3">@lang('commun.susbcribe_offer')</h2>

                    {!! Form::open(['route' => ['front.subscribeOffers'], 'method' => 'post', 'id' => 'subscribe-form', 'class' => 'form-inline container-fluid form-input-nice']) !!}
                        @csrf <!-- {{ csrf_field() }} -->
                        <div class="col-12 col-lg-9 p-0">
                            <div class="form-row">
{{--                            <div>--}}
                                <div class="input-field col-12">
                                    {{Form::openGroup('email', null)}}
                                        {{ Form::input('text', 'email', null, ['placeholder' => __('commun.email'), 'autofocus' => false]) }}
                                        {{ Form::label('email', __('commun.email'), ['class' => 'justify-content-start']) }}
                                    {{Form::closeGroup()}}
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-3 p-0 text-center">
                            <div class="btn btn-secondary-brand">
                                <a class="text-uppercase text-white font-weight-bold btn-subscribe" href="#">
                                    @lang('commun.subscribe')
                                </a>
                            </div>
                        </div>
                    {!! Form::close() !!}

                    <p class="text-muted small-text">@lang('commun.rgpd_mention') <a href="{{ route('front.privacyPolicy', ['slug' => str_slug(__('commun.rgpd_policy')) ]) }}">@lang('commun.rgpd_policy')</a></p>
                </div>


                <div class="container-fluid mb-3">
                    <div class="block block-rounded bulle py-4 px-3 px-md-5">
                        <div class="block-header justify-content-center justify-content-md-start">
                            <h4 class="mb-3">@lang('commun.advicesbuy')</h4>
                        </div>
                        <div class="block-content">
                            <p>
                                @lang('commun.buywell_line1')
                            </p>
                            <p>
                                @lang('commun.buywell_line2')
                            </p>
                            <p>
                                @lang('commun.buywell_line3')
                            </p>
                            <p>
                                @lang('commun.buywell_line4')
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.btn-subscribe').on('click', function (e) {
                e.preventDefault();
                $('#subscribe-form').submit();
            });
        });
    </script>
@endpush
