<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-800 rounded-xl p-6 shadow-md border border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
            <div class="bg-primary-500 dark:bg-primary-600 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            Bulk Input Nilai – {{ $classRoom->schoolClass->name }}
            <span class="text-lg font-normal text-gray-600 dark:text-gray-300">({{ $classRoom->academicYear->name }})</span>
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Wali Kelas: <span class="font-semibold text-gray-900 dark:text-white">{{ $classRoom->teacher->nama }}</span>
        </p>
    </div>

    {{-- FORM --}}
    <form wire:submit.prevent="saveScores" class="space-y-6">

        {{-- SEMESTER TABS --}}
        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-1 inline-flex gap-1 border border-gray-200 dark:border-gray-700">
            <button
                type="button"
                wire:click="$set('semester','ganjil')"
                class="{{ $semester === 'ganjil'
                    ? 'bg-white dark:bg-gray-700 text-primary-600 dark:text-primary-300 shadow-md font-semibold'
                    : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700'
                }} px-6 py-2 rounded-md text-sm font-medium transition-all duration-200">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Ganjil
                </span>
            </button>

            <button
                type="button"
                wire:click="$set('semester','genap')"
                class="{{ $semester === 'genap'
                    ? 'bg-white dark:bg-gray-700 text-primary-600 dark:text-primary-300 shadow-md font-semibold'
                    : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700'
                }} px-6 py-2 rounded-md text-sm font-medium transition-all duration-200">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Genap
                </span>
            </button>
        </div>

        {{-- FILTER MAPEL --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-md">
            <div class="flex flex-wrap items-center gap-4">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter Mapel:
                </span>

                @foreach($subjects as $subject)
                    <label class="flex items-center gap-2 px-3 py-1.5 rounded-lg border-2
                        {{ in_array($subject->id, $selectedSubjects)
                            ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/30'
                            : 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 hover:border-primary-400 dark:hover:border-primary-500 hover:bg-gray-50 dark:hover:bg-gray-600'
                        }} cursor-pointer transition-all duration-200">
                        <input
                            type="checkbox"
                            wire:model="selectedSubjects"
                            value="{{ $subject->id }}"
                            class="rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-400">
                        <span class="text-sm {{ in_array($subject->id, $selectedSubjects)
                            ? 'font-semibold text-primary-700 dark:text-primary-300'
                            : 'font-medium text-gray-700 dark:text-gray-200'
                        }}">
                            {{ $subject->name }}
                        </span>
                    </label>
                @endforeach

                <button
                    type="button"
                    wire:click="selectAllSubjects"
                    class="ml-auto px-4 py-1.5 bg-primary-100 dark:bg-primary-900/40 hover:bg-primary-200 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Pilih Semua
                </button>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow-md">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-800">
                    <tr>
                        <th class="p-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Nama Siswa
                            </div>
                        </th>
                        @foreach($subjects as $subject)
                            <th colspan="3" class="p-3 text-center font-semibold text-gray-700 dark:text-gray-200 {{ in_array($subject->id, $selectedSubjects) ? 'bg-primary-100 dark:bg-primary-900/40' : '' }}">
                                <div class="flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    {{ $subject->name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">
                                    <span class="inline-block px-2 py-0.5 bg-blue-100 dark:bg-blue-900/40 rounded text-blue-700 dark:text-blue-300">Daily</span>
                                    <span class="inline-block px-2 py-0.5 bg-yellow-100 dark:bg-yellow-900/40 rounded text-yellow-700 dark:text-yellow-300 ml-1">UTS</span>
                                    <span class="inline-block px-2 py-0.5 bg-red-100 dark:bg-red-900/40 rounded text-red-700 dark:text-red-300 ml-1">UAS</span>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach($students as $student)
                        <tr class="border-t dark:border-gray-700 {{ $loop->odd ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-150">
                            <td class="p-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-semibold text-xs">
                                        {{ substr($student->nama, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $student->nama }}</span>
                                </div>
                            </td>

                            @foreach($subjects as $subject)
                                @php
                                    $key = "{$student->id}_{$subject->id}";
                                    $active = in_array($subject->id, $selectedSubjects);
                                @endphp

                                @foreach(['daily_score','uts_score','uas_score'] as $field)
                                    <td class="p-2 text-center">
                                        @if($active)
                                            <input
                                                type="number"
                                                min="0"
                                                max="100"
                                                wire:model.lazy="scores.{{ $key }}.{{ $field }}"
                                                class="w-14 rounded-lg border-2 border-gray-300 dark:border-gray-600 
                                                       focus:border-primary-500 focus:ring-2 focus:ring-primary-200 dark:focus:ring-primary-800
                                                       dark:bg-gray-900 text-center text-sm font-medium
                                                       transition-all duration-200">
                                        @else
                                            <span class="text-gray-400 dark:text-gray-600">–</span>
                                        @endif
                                    </td>
                                @endforeach
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="flex flex-wrap gap-3 items-center">
            <button
                type="submit"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-500 dark:to-indigo-500 hover:from-blue-700 hover:to-indigo-700 dark:hover:from-blue-600 dark:hover:to-indigo-600 text-white font-semibold px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                Simpan Semua
            </button>

            <a
                href="{{ \App\Filament\Resources\ClassRoomResource::getUrl('index') }}"
                class="inline-flex items-center gap-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

    </form>
</div>
