@component('mail::message')
# Price changed

Price of {{ $apartment->name }} is now {{ $apartment->price }} !

@endcomponent
