@extends('layouts.front')
{{--@section('pagetitle') {{ config('app.name', 'Laravel') }} @endsection--}}

@section('banner')
    <div class="video pt-8 pt-lg-12 container-fluid" style="background-image: url('{{ asset($page->thumbnail_path) }}');">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h1 class="flex-sm-fill font-size-h1 font-weight-bold mt-2 mb-0 mb-sm-5 text-uppercase text-white">
                    {{ $page->title }}
                </h1>
            </div>
        </div>
    </div>
@endsection

@section('breadcrumbs')
    @include('layouts.inc.breadcrumbs', [
        'breadcrumbs' => [
            ['name' => 'commun.news', 'route' => 'front.news'],
            ['name' => $page->title],
        ]
        ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 offset-lg-1 col-lg-10 offset-xl-2 col-xl-8 p-0 px-md-2">
                <div id="wrapper" class="container-fluid mb-5">

                    @include('grapesjs::viewgrapes')

                </div>

                <div class="container-fluid mb-5">
                    <div class="row d-flex justify-content-center">
                        <div class="col-12 col-lg-4">
                            @include('layouts.inc.contact-information')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
@endpush