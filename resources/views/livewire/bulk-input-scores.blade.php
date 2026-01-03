<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
            Bulk Input Nilai – {{ $classRoom->schoolClass->name }}
            ({{ $classRoom->academicYear->name }})
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Wali Kelas: {{ $classRoom->teacher->nama }}
        </p>
    </div>

    {{-- FORM --}}
    <form wire:submit.prevent="saveScores" class="space-y-6">

        {{-- SEMESTER (FILAMENT BUTTON) --}}
        <div class="flex gap-2">
            <button
                type="button"
                wire:click="$set('semester','ganjil')"
                class="fi-btn {{ $semester === 'ganjil' ? 'fi-btn-primary' : 'fi-btn-gray' }}">
                Ganjil
            </button>

            <button
                type="button"
                wire:click="$set('semester','genap')"
                class="fi-btn {{ $semester === 'genap' ? 'fi-btn-primary' : 'fi-btn-gray' }}">
                Genap
            </button>
        </div>

        {{-- FILTER MAPEL --}}
        <div class="flex flex-wrap items-center gap-4">
            <span class="text-sm text-gray-600 dark:text-gray-300">
                Filter Mapel:
            </span>

            @foreach($subjects as $subject)
                <label class="flex items-center gap-1 text-sm">
                    <input
                        type="checkbox"
                        wire:model="selectedSubjects"
                        value="{{ $subject->id }}"
                        class="rounded border-gray-300 dark:border-gray-700">
                    {{ $subject->name }}
                </label>
            @endforeach
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="p-2 text-left">Nama Siswa</th>
                        @foreach($subjects as $subject)
                            <th colspan="3" class="p-2 text-center">
                                {{ $subject->name }}
                                <div class="text-xs text-gray-500">
                                    Daily | UTS | UAS
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach($students as $student)
                        <tr class="border-t dark:border-gray-700">
                            <td class="p-2">
                                {{ $student->nama }}
                            </td>

                            @foreach($subjects as $subject)
                                @php
                                    $key = "{$student->id}_{$subject->id}";
                                    $active = in_array($subject->id, $selectedSubjects);
                                @endphp

                                @foreach(['daily_score','uts_score','uas_score'] as $field)
                                    <td class="p-1 text-center">
                                        @if($active)
                                            <input
                                                type="number"
                                                min="0"
                                                max="100"
                                                wire:model.lazy="scores.{{ $key }}.{{ $field }}"
                                                class="w-14 rounded-md border-gray-300
                                                       dark:bg-gray-900
                                                       dark:border-gray-700
                                                       text-center text-sm">
                                        @else
                                            <span class="text-gray-400">–</span>
                                        @endif
                                    </td>
                                @endforeach
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ACTION BUTTON (FILAMENT ONLY) --}}
        <div class="flex gap-3">
            <button type="submit" class="fi-btn fi-btn-primary">
                Simpan Semua
            </button>

            <a
                href="{{ \App\Filament\Resources\ClassRoomResource::getUrl('index') }}"
                class="fi-btn fi-btn-gray">
                Kembali
            </a>
        </div>

    </form>
</div>
