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
                        @lang('Bonjour, vous avez un nouveau message :')
                    </td>
                </tr>
                <tr>
                    <td  style="height: 8px"></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;font-size: 1.2em">
                        @lang('Rappel de l\'objet :') {{ $data['subject'] }}
                    </td>
                </tr>
                <tr>
                    <td>
                        @lang('De :') {{ $data['name'].' - '.$data['firstname'] }}
                    </td>
                </tr>
                <tr>
                    <td  style="height: 10px"></td>
                </tr>
                <tr>
                    <td>
                        @lang('commun.message') {{ $data['message'] }}
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>@lang('commun.coordonate') : </strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>@lang('commun.email') : </strong> {{ $data['email'] }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>@lang('commun.tel') : </strong> {{ $data['phone'] }}
                    </td>
                </tr>

                <tr>
                    <td  style="height: 30px"></td>
                </tr>
            </table>
        </td>
        <td style="width:10%"></td>
    </tr>
</table>
@include('emails.layouts.footer')
