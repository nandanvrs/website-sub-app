@component('mail::message')

Hello {{ $data['name'] }}, a new post is published

@component('mail::panel')

    Title : {{ $data['title'] }} <br>
    Description : {{ $data['description'] }} <br>
@endcomponent

{{ config('app.name') }}
@endcomponent
