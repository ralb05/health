<x-layouts.app title="Agendar cita">
    <div class="flex flex-1 flex-col px-6 pt-8 pb-6"
         x-data="{
            days: @js($days),
            selected: null,
            slot: null,
            get currentDay() { return this.days.find(d => d.date === this.selected) },
         }"
         x-init="selected = days.length ? days[0].date : null">

        {{-- Volver --}}
        <a href="{{ route('especialistas.show', $doctor) }}" class="inline-flex items-center gap-1 text-sm font-medium text-navy-400 hover:text-navy-600">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Volver
        </a>

        {{-- Especialista --}}
        <div class="mt-5 flex items-center gap-3">
            <x-avatar :name="$doctor->full_name" :src="$doctor->photo_url" size="h-14 w-14" />
            <div>
                <h1 class="font-semibold text-navy-700">{{ $doctor->full_name }}</h1>
                <p class="text-sm text-navy-400">{{ $doctor->title }} · {{ $doctor->price_formatted }}</p>
            </div>
        </div>

        @if (session('error'))
            <div class="mt-5 rounded-xl bg-red-50 px-4 py-3 text-sm font-medium text-red-600">
                {{ session('error') }}
            </div>
        @endif

        @if ($days->isEmpty())
            <div class="mt-10 flex flex-1 flex-col items-center justify-center gap-3 text-center text-navy-400">
                <svg class="h-12 w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 3v3m10-3v3M4 9h16M5 6h14a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1Z"/></svg>
                <p class="text-sm">Este especialista no tiene cupos disponibles por ahora.</p>
            </div>
        @else
            {{-- Selector de día --}}
            <h2 class="mt-7 mb-3 text-sm font-semibold tracking-wide text-navy-400 uppercase">Elige el día</h2>
            <div class="-mx-6 flex gap-2 overflow-x-auto px-6 pb-1">
                <template x-for="d in days" :key="d.date">
                    <button type="button"
                            @click="selected = d.date; slot = null"
                            :class="selected === d.date ? 'bg-navy-700 text-white ring-navy-700' : 'bg-white text-navy-600 ring-navy-100 hover:ring-navy-200'"
                            class="flex w-16 shrink-0 flex-col items-center gap-0.5 rounded-2xl py-3 ring-1 transition">
                        <span class="text-[11px] uppercase" x-text="d.label.split(' ')[0]"></span>
                        <span class="text-lg font-semibold leading-none" x-text="d.label.split(' ')[1]"></span>
                        <span class="text-[11px]" x-text="d.label.split(' ')[2]"></span>
                    </button>
                </template>
            </div>

            {{-- Selector de hora --}}
            <h2 class="mt-7 mb-3 text-sm font-semibold tracking-wide text-navy-400 uppercase">
                Elige la hora <span class="text-navy-300 normal-case" x-text="currentDay ? '· ' + currentDay.weekday : ''"></span>
            </h2>
            <div class="grid grid-cols-3 gap-2.5">
                <template x-for="s in (currentDay ? currentDay.slots : [])" :key="s.value">
                    <button type="button"
                            @click="slot = s.value"
                            :class="slot === s.value ? 'bg-navy-700 text-white ring-navy-700' : 'bg-white text-navy-600 ring-navy-100 hover:ring-navy-200'"
                            class="rounded-xl py-3 text-sm font-medium ring-1 transition"
                            x-text="s.label"></button>
                </template>
            </div>

            {{-- Acción --}}
            <form method="POST" action="{{ route('booking.store', $doctor) }}" class="mt-8">
                @csrf
                <input type="hidden" name="slot" :value="slot">
                <x-button type="submit" x-bind:disabled="!slot"
                          x-bind:class="!slot ? 'opacity-40 pointer-events-none' : ''">
                    Continuar
                </x-button>
            </form>
        @endif
    </div>
</x-layouts.app>
