<div class="col-12 {{ $logo['col'] }}">
    <a href="{{ $logo['href'] }}" target="_blank">
        <figure class="options-container fx-item-zoom-in">
            <img class="img-fluid options-item"
                 src="{{ $logo['src'] }}"
                 alt="{{ $logo['alt'] }}"
                 itemprop="image" />
            @if( !empty($logo['src']) )
                <figcaption class="options-overlay bg-white-75">
                    <div class="options-overlay-content">
                        <h4 class="text-black">
                            {{ $logo['name'] }}
                        </h4>
                    </div>
                </figcaption>
            @else
                <figcaption>
                    <h4>
                        {{ $logo['name'] }}
                    </h4>
                </figcaption>
            @endif
        </figure>
    </a>
</div>