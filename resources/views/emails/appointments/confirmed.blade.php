@php
    $doctor = $appointment->doctor;
@endphp

<x-mail::message>
# ¡Tu cita está confirmada! ✅

Hola {{ \Illuminate\Support\Str::of($appointment->patient->name)->before(' ') }}, tu pago se realizó con éxito y tu consulta quedó agendada.

<x-mail::panel>
**{{ $doctor->full_name }}** — {{ $doctor->title }}<br>
**Fecha:** {{ $appointment->starts_at->isoFormat('dddd D [de] MMMM [de] YYYY') }}<br>
**Hora:** {{ $appointment->starts_at->format('g:i a') }}<br>
**Tipo:** Consulta on-line (videollamada)<br>
**Valor pagado:** {{ $appointment->price_formatted }}
</x-mail::panel>

La consulta se realizará por **videollamada**. Recibirás el enlace de acceso antes de la cita.

<x-mail::button :url="route('citas.show', $appointment)">
Ver mi cita
</x-mail::button>

Tu información está protegida — consulta 100% confidencial. 💜

Gracias,<br>
**{{ config('app.name') }}**
</x-mail::message>
