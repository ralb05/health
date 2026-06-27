@props([
    'doctor',
])

<a href="{{ route('especialistas.show', $doctor) }}"
   class="flex items-center gap-3 rounded-2xl bg-white p-3 ring-1 ring-navy-100/70 shadow-sm transition hover:ring-navy-200 active:bg-navy-50">
    <x-avatar :name="$doctor->full_name" :src="$doctor->photo_url" size="h-16 w-16" />

    <div class="min-w-0 flex-1">
        <p class="truncate font-semibold text-navy-700">{{ $doctor->full_name }}</p>
        <p class="text-sm text-navy-400">{{ $doctor->title }}</p>
        <div class="mt-1 flex items-center gap-2">
            <x-rating :value="$doctor->rating" />
            <span class="text-xs text-navy-300">· {{ $doctor->experience_years }} años de exp.</span>
        </div>
    </div>

    <svg class="h-5 w-5 shrink-0 text-navy-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="m9 18 6-6-6-6"/>
    </svg>
</a>
