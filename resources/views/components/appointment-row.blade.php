@props([
    'appointment',
])

@php
    $doctor = $appointment->doctor;
    $statusStyles = [
        \App\Models\Appointment::STATUS_PENDING => 'bg-amber-100 text-amber-700',
        \App\Models\Appointment::STATUS_CONFIRMED => 'bg-mint-100 text-mint-500',
        \App\Models\Appointment::STATUS_COMPLETED => 'bg-navy-100 text-navy-600',
        \App\Models\Appointment::STATUS_CANCELLED => 'bg-red-50 text-red-500',
        \App\Models\Appointment::STATUS_EXPIRED => 'bg-red-50 text-red-500',
    ];
@endphp

<a href="{{ route('citas.show', $appointment) }}"
   class="flex items-center gap-3 rounded-2xl bg-white p-3 ring-1 ring-navy-100/70 shadow-sm transition hover:ring-navy-200 active:bg-navy-50">
    <x-avatar :name="$doctor->full_name" :src="$doctor->photo_url" size="h-14 w-14" />

    <div class="min-w-0 flex-1">
        <p class="truncate font-semibold text-navy-700">{{ $doctor->full_name }}</p>
        <p class="text-sm text-navy-400">
            {{ $appointment->starts_at->isoFormat('ddd D MMM') }} · {{ $appointment->starts_at->format('g:i a') }}
        </p>
        <span class="mt-1 inline-block rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $statusStyles[$appointment->status] ?? 'bg-navy-100 text-navy-600' }}">
            {{ $appointment->status_label }}
        </span>
    </div>

    <svg class="h-5 w-5 shrink-0 text-navy-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
</a>
