@include('emails.layouts.header')
<table>
    <tr>
    </tr>
    <tr>
        <td  style="height: 40px"></td>
    </tr>
</table>
<table>
    <tr>
        <td style="width:10%"></td>
        <td style="width:80%">
            <table>
                <tr>
                    <td style="font-weight: bold;font-size: 1.4em">
                        @lang('commun.last_offers')
                    </td>
                </tr>
                <tr>
                    <td  style="height: 20px"></td>
                </tr>

                @foreach($properties as $property)
                    <tr>
                        <td>
                            <strong>{{ $property->title }}</strong> - {{ $property->zipcode.' '.$property->city }}
                        </td>
                    </tr>
                    <tr>
                        <td  style="height: 8px"></td>
                    </tr>
                    <tr>
                        <td>
                            {{ $property->description }}
                        </td>
                    </tr>
                    <tr>
                        <td  style="height: 8px"></td>
                    </tr>
                    <tr>
                        <td>
                            <a href="{{ route('front.viewProperty', ['property' => $property->id, 'date' => $property->date_updated_pericles->format('Y-m-d'), 'slug' => $property->slug] ) }}">{{ route('front.viewProperty', ['property' => $property->id, 'date' => $property->date_updated_pericles->format('Y-m-d'), 'slug' => $property->slug] ) }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td  style="height: 25px"></td>
                    </tr>
                @endforeach

                <tr>
                    <td  style="height: 30px"></td>
                </tr>
            </table>
        </td>
        <td style="width:10%"></td>
    </tr>
</table>
@include('emails.layouts.footer')
