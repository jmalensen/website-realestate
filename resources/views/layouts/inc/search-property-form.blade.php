<div onKeyPress="return checkSubmit(event)">
    {!! Form::open(['route' => ['front.viewListProperties'], 'method' => 'GET', 'id' => 'search-form', 'class' => 'form-inline container-fluid']) !!}
    {{--@csrf <!-- {{ csrf_field() }} -->--}}
        {{Form::considerRequest(true)}}
        <div class="col-12 col-lg-9 p-0">
            {{--                        {{Form::textGroup('zipcode', __('commun.zipcode') )}}--}}
            <div class="form-row">
                {{Form::openGroup('search[zipcode]', null, ['class' => 'col-12 col-sm-6 col-lg-3 mb-sm-2 mb-lg-0'])}}
                    {{ Form::input('text', 'search[zipcode]', null, ['placeholder' => __('commun.zipcode').'*', 'autofocus' => false]) }}
                {{Form::closeGroup()}}

                {{Form::openGroup('search[maxprice]', null, ['class' => 'col-12 col-sm-6 col-lg-3 mb-sm-2 mb-lg-0'])}}
                    {{ Form::input('text', 'search[maxprice]', null, ['placeholder' => __('commun.maxprice'), 'autofocus' => false]) }}
                {{Form::closeGroup()}}

                @php
                    $zone = [
                        '0' => __('commun.choose_area'),
                        '5' => __('commun.distance_5km'),
                        '10' => __('commun.distance_10km'),
                        '15' => __('commun.distance_15km'),
                    ];
                @endphp
                {{Form::openGroup('search[zone]', null, ['class' => 'col-12 col-sm-12 col-lg-3 mb-sm-2 mb-lg-0'])}}
                    {{Form::select('search[zone]', $zone)}}
                {{Form::closeGroup()}}

                @php
                    $typeproperties = [
                        '0'                                 => __('commun.choose_type'),
                        \App\Models\Property::TYPE_HOUSE    => __('commun.property_type1'),
                        \App\Models\Property::TYPE_FLAT     => __('commun.property_type2'),
                        \App\Models\Property::TYPE_BUILDING => __('commun.property_type3'),
                        \App\Models\Property::TYPE_FIELD    => __('commun.property_type4'),
                        \App\Models\Property::TYPE_NEW      => __('commun.property_type5'),
                    ];
                @endphp
                {{Form::openGroup('search[typeproperty]', null, ['class' => 'col-12 col-sm-12 col-lg-3 mb-sm-2 mb-lg-0'])}}
                    {{Form::select('search[typeproperty]', $typeproperties)}}
                {{Form::closeGroup()}}
            </div>
        </div>

        <div class="col-12 col-lg-3 p-0 text-center">
            <div class="btn btn-secondary-brand">
                <a class="text-uppercase text-white font-weight-bold btn-search" href="#">
                    <i class="fa fa-search"></i> @lang('commun.search')
                </a>
            </div>
        </div>
    {!! Form::close() !!}
</div>
@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.btn-search').on('click', function (e) {
                e.preventDefault();
                $('#search-form').submit();
            });
        });

        function checkSubmit(e) {
            if(e && e.keyCode == 13) {
                $('#search-form').submit();
            }
        }
    </script>
@endpush