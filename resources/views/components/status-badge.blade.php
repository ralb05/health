@props([
    'status',
    'label' => null,
])

@php
    $styles = [
        \App\Models\Appointment::STATUS_PENDING => 'bg-amber-100 text-amber-700',
        \App\Models\Appointment::STATUS_CONFIRMED => 'bg-mint-100 text-mint-500',
        \App\Models\Appointment::STATUS_COMPLETED => 'bg-navy-100 text-navy-600',
        \App\Models\Appointment::STATUS_CANCELLED => 'bg-red-50 text-red-500',
        \App\Models\Appointment::STATUS_EXPIRED => 'bg-red-50 text-red-500',
        // pagos
        'approved' => 'bg-mint-100 text-mint-500',
        'rejected' => 'bg-red-50 text-red-500',
        'refunded' => 'bg-navy-100 text-navy-600',
        'pending' => 'bg-amber-100 text-amber-700',
    ];
    $labels = [
        'approved' => 'Aprobado', 'rejected' => 'Rechazado',
        'refunded' => 'Reembolsado', 'pending' => 'Pendiente',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold '.($styles[$status] ?? 'bg-navy-100 text-navy-600')]) }}>
    {{ $label ?? $labels[$status] ?? $status }}
</span>
