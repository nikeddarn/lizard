@component('mail::message')

    <div>

        <h1 style="text-align: center; margin-bottom: 2rem">{{ $headerText }}</h1>

        <p>{{ $bodyText }}</p>

    </div>

@endcomponent
