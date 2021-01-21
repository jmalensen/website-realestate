@include('emails.layouts.header')
{{ config('app.name', 'Laravel') }}

@lang('Bonjour, Vous avez un nouveau message :')

@lang('commun.last_offers')

@foreach($properties as $property)
    {{ $property->title }} - {{ $property->zipcode.' '.$property->city }}
    {{ $property->description }}
    {{ route('front.viewProperty', ['property' => $property->id, 'date' => $property->date_updated_pericles->format('Y-m-d'), 'slug' => $property->slug] ) }}
@endforeach

@include('emails.layouts.footer')
