<ul class="flat-list p-0" style="visibility: visible;">
    @forelse($properties as $property)
        <li class="one-flat mb-4 overflow-hidden" data-product="{{ $property->code_immo }}">
            <article class="container-fluid flat-container" itemscope="" itemtype="https://schema.org/Product">
                <div class="row">

                    {{--------------------------- Image block --------------------------}}
                    <section class="left-block col-12 col-md-7 p-0 overflow-hidden"
                             data-product="{{ $property->code_immo }}">
                        <div class="flat-img-container">
                            <a class="flat-img-link img-link-hover"
                               href="{{ route('front.viewProperty', ['property' => $property->id, 'date' => $property->date_updated_immo->format('Y-m-d'), 'slug' => $property->slug] ) }}"
                               title="{{ $property->getTitle() }}"
                               itemprop="url"
                               style="visibility: visible; background-image: url({{ asset( $property->thumbnail_path ) }});">
                                <img class="img-responsive"
                                     src="{{ asset( $property->thumbnail_path ) }}"
                                     alt="{{ $property->getTitle() }}"
                                     title="{{ $property->getTitle() }}"
                                     itemprop="image"
                                     style="display: none; visibility: visible;width:100%;">
                                <div class="mask" style="visibility: visible;"></div>
                            </a>
                        </div>
                    </section>
                    {{--------------------------- End image block --------------------------}}

                    {{--------------------------- Details block --------------------------}}
                    <section class="right-block col-12 col-md-5 px-4 py-4">
                        <div class="right-block-container container-fluid">
                            <div class="row right-block-top mb-4">
                                {{--------------------------- Visible part block --------------------------}}
                                <div class="right-block-top-left mb-3 mb-md-0">
                                    <h4 itemprop="name" class="title-flat">
                                        <i class="fas fa-grip-vertical color-primary"></i> <a class="flat-name"
                                           href="{{ route('front.viewProperty', ['property' => $property->id, 'date' => $property->date_updated_immo->format('Y-m-d'), 'slug' => $property->slug] ) }}"
                                           title="{{ $property->getTitle() }}"
                                           itemprop="url">
                                            <span> {{ str_limit($property->getTitle(), $limit = 30, $end = '...') }}</span>
                                        </a>
                                    </h4>
                                    <div class="flat-desc text-regular" itemprop="description">
                                        <div class="flat">
                                            <span>
{{--                                                @lang($property->property_type->type)--}}
                                            </span>
                                            <span>
                                                {{ $property->nb_room }} @lang('commun.room')
                                            </span>
                                        </div>
                                    </div>
                                    <div class="content-price text-regular">
                                        <div class="price">
                                            <span>
                                                {{ $property->price }} €
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-regular">
                                        <span>
                                            @lang('commun.fees_to_saler')
                                        </span>
                                    </div>
                                </div>
                                {{--------------------------- End visible part block --------------------------}}

                                {{--------------------------- Hover visible part block --------------------------}}
                                <div class="right-block-top-right mb-3 mb-md-0">
                                    <div class="features caracteristics">
                                        <div>
                                            <i class="fas fa-layer-group color-primary"></i> <span class="caracteristics-title">@lang('commun.area')</span>
                                            {{ $property->living_area }} m²
                                        </div>
                                        <div>
                                            <i class="fas fa-layer-group color-primary"></i> <span class="caracteristics-title">@lang('commun.nb_room')</span>
                                            {{ $property->nb_room }}
                                        </div>
                                        <div>
                                            <i class="fas fa-bed color-primary"></i> <span class="caracteristics-title">@lang('commun.bedroom')</span>
                                            {{ $property->bedroom }}
                                        </div>
                                        <div>
                                            <i class="fas fa-level-up-alt color-primary"></i> <span class="caracteristics-title">@lang('commun.floors')</span>
                                            {{ $property->floor }}
                                        </div>
                                    </div>
                                </div>
                                {{--------------------------- End Hover visible part block --------------------------}}
                            </div>

                            <div class="row right-block-bottom d-flex justify-content-end">
                                <a class="favorite pt-2"
                                      href="#"
                                      title="@lang('commun.add_to_favorites')"
                                      itemprop="url"
                                      data-content="{{$property->id}}">
                                    <i class="{{ (!empty($hasAuthUser) && $property->isFavoriteOfUser() )? 'fa' : 'fal' }} fa-2x fa-heart color-primary"></i>
                                </a>

                                <div class="btn btn-secondary-brand">
                                    <a class="text-white" href="{{ route('front.viewProperty', ['property' => $property->id, 'date' => $property->date_updated_immo->format('Y-m-d'), 'slug' => $property->slug] ) }}">@lang('commun.knowmore')</a>
                                </div>
                            </div>

                            <div class="button-close">
                                <i class="far fa-times-circle"></i>
                            </div>
                        </div>
                    </section>
                    {{--------------------------- End Details block --------------------------}}

                </div>
            </article>
        </li>
    @empty
        <li>@lang('commun.no_properties')</li>
    @endforelse
</ul>

@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.right-block').on('click', function(){
                $(this).parent().parent().parent().addClass('opened');
            });

            $('.button-close').on('click', function(e){
                e.stopPropagation();
                $(this).parent().parent().parent().parent().parent().removeClass('opened');
            });

            $('.favorite').on('click', function(e){
                e.preventDefault();
                $(this).find('i').toggleClass('fa').toggleClass('fal');
                let property_id = $(this).attr('data-content');
                changePropertyToFavorite(property_id);
            });
        });

        function changePropertyToFavorite (property_id){
            let hasAuthUser = {{ (!empty($hasAuthUser) )? 'true' : 'false'}};
            if(hasAuthUser == true){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'{{route('front.changeFavoriteUser')}}',
                    type:'POST',
                    dataType:'json',
                    data:{
                        property_id :property_id
                    },
                    success:function(res){
                        if(res.success){
                            swal('', res.message, "success");
                        } else{
                            swal("Oups!", res.message, "error");
                        }
                    },
                    error:function(){
                        swal("Oups!", res.message, "error");
                    }
                });
            } else{
                window.location.href = '{{ route('login') }}';
            }
        }
    </script>
@endpush