@include('emails.layouts.header')
{{ config('app.name', 'Laravel') }}

@lang('Bonjour, Vous avez un nouveau message :')

@lang('Rappel de l\'objet :') {{ $data['subject'] }}

@lang('commun.message') {{ $data['message'] }}

@lang('commun.contact_information')
@lang('commun.email') : {{ $data['email'] }}
@lang('commun.tel') : {{ $data['phone'] }}

@include('emails.layouts.footer')
