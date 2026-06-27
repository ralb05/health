@php
    $doctor = $appointment->doctor;
@endphp

<x-mail::message>
# ⏰ Recordatorio de tu cita

Hola {{ \Illuminate\Support\Str::of($appointment->patient->name)->before(' ') }}, te recordamos que tienes una consulta próxima.

<x-mail::panel>
**{{ $doctor->full_name }}** — {{ $doctor->title }}<br>
**Fecha:** {{ $appointment->starts_at->isoFormat('dddd D [de] MMMM') }}<br>
**Hora:** {{ $appointment->starts_at->format('g:i a') }}<br>
**Tipo:** Consulta on-line (videollamada)
</x-mail::panel>

@if ($appointment->meeting_url)
Puedes entrar a la videollamada con este enlace:

<x-mail::button :url="$appointment->meeting_url">
Entrar a la videollamada
</x-mail::button>
@else
Recibirás el enlace de la videollamada antes de la cita.
@endif

<x-mail::button :url="route('citas.show', $appointment)" color="success">
Ver mi cita
</x-mail::button>

Te esperamos. 💜

Gracias,<br>
**{{ config('app.name') }}**
</x-mail::message>
