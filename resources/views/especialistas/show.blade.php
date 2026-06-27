<x-layouts.app :title="$doctor->full_name">
    <div class="flex flex-1 flex-col px-6 pt-8 pb-6">
        {{-- Volver --}}
        <a href="{{ route('especialidades.show', $doctor->specialty) }}" class="inline-flex items-center gap-1 text-sm font-medium text-navy-400 hover:text-navy-600">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Volver
        </a>

        {{-- Encabezado del especialista --}}
        <div class="mt-5 flex items-center gap-4">
            <x-avatar :name="$doctor->full_name" :src="$doctor->photo_url" size="h-20 w-20" />
            <div class="min-w-0">
                <h1 class="text-xl font-semibold text-navy-700">{{ $doctor->full_name }}</h1>
                <p class="text-sm text-navy-400">{{ $doctor->title }}</p>
                <div class="mt-1">
                    <x-rating :value="$doctor->rating" :count="$doctor->reviews_count" />
                </div>
            </div>
        </div>

        {{-- Etiquetas --}}
        @if (!empty($doctor->tags))
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($doctor->tags as $tag)
                    <span class="rounded-full bg-lavender-100 px-3 py-1 text-xs font-medium text-navy-600">{{ $tag }}</span>
                @endforeach
            </div>
        @endif

        {{-- Datos rápidos --}}
        <div class="mt-5 grid grid-cols-2 gap-3">
            <x-card padding="p-4">
                <p class="text-xs text-navy-300">Experiencia</p>
                <p class="mt-0.5 font-semibold text-navy-700">{{ $doctor->experience_years }} años</p>
            </x-card>
            <x-card padding="p-4">
                <p class="text-xs text-navy-300">Tipo de consulta</p>
                <p class="mt-0.5 font-semibold text-navy-700">On-line</p>
            </x-card>
        </div>

        {{-- Sobre el especialista --}}
        @if ($doctor->bio)
            <h2 class="mt-6 mb-2 font-semibold text-navy-700">Sobre el especialista</h2>
            <p class="text-sm leading-relaxed text-navy-500">{{ $doctor->bio }}</p>
        @endif

        {{-- Confidencialidad --}}
        <div class="mt-5 flex items-center gap-2 rounded-2xl bg-mint-100 px-4 py-3 text-sm text-navy-600">
            <svg class="h-5 w-5 shrink-0 text-mint-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v5c0 4.2-2.9 7.5-7 9-4.1-1.5-7-4.8-7-9V6l7-3Z"/></svg>
            Tu información está protegida. Consulta 100% confidencial.
        </div>
    </div>

    {{-- Barra inferior fija: precio + agendar --}}
    <div class="sticky bottom-0 border-t border-navy-100 bg-white/95 px-6 py-4 backdrop-blur">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs text-navy-300">Precio consulta</p>
                <p class="text-lg font-semibold text-navy-700">{{ $doctor->price_formatted }}</p>
            </div>
            <x-button :href="route('booking.create', $doctor)" class="!w-auto px-8">Agendar cita</x-button>
        </div>
    </div>
</x-layouts.app>
