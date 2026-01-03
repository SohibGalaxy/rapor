<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\{
    ClassRoom,
    Subject,
    Score
};
use Filament\Notifications\Notification;

class BulkInputScores extends Component
{
    public ClassRoom $classRoom;
    public string $semester = 'ganjil';

    /**
     * scores[
     *   studentId_subjectId => [
     *      daily_score,
     *      uts_score,
     *      uas_score
     *   ]
     * ]
     */
    public array $scores = [];

    public array $selectedSubjects = [];

    protected $queryString = [
        'semester' => ['except' => 'ganjil'],
    ];

    public function mount(ClassRoom $classRoom)
    {
        $this->classRoom = $classRoom;
        $this->selectedSubjects = Subject::pluck('id')->toArray();
        $this->loadScores();
    }

    public function updatedSemester()
    {
        $this->loadScores();
    }

    public function selectAllSubjects()
    {
        $this->selectedSubjects = Subject::pluck('id')->toArray();
    }

    private function loadScores(): void
    {
        $this->scores = [];

        $existing = Score::where('class_room_id', $this->classRoom->id)
            ->where('semester', $this->semester)
            ->get();

        foreach ($existing as $score) {
            $key = "{$score->student_id}_{$score->subject_id}";
            $this->scores[$key] = [
                'daily_score' => $score->daily_score,
                'uts_score'   => $score->uts_score,
                'uas_score'   => $score->uas_score,
            ];
        }
    }

    public function saveScores()
    {
        $saved = 0;

        foreach ($this->scores as $key => $value) {
            [$studentId, $subjectId] = explode('_', $key);

            if (!in_array((int) $subjectId, $this->selectedSubjects)) {
                continue;
            }

            if (
                blank($value['daily_score'] ?? null) &&
                blank($value['uts_score'] ?? null) &&
                blank($value['uas_score'] ?? null)
            ) {
                continue;
            }

            Score::updateOrCreate(
                [
                    'student_id'    => $studentId,
                    'subject_id'    => $subjectId,
                    'class_room_id' => $this->classRoom->id,
                    'semester'      => $this->semester,
                ],
                [
                    'daily_score' => $value['daily_score'],
                    'uts_score'   => $value['uts_score'],
                    'uas_score'   => $value['uas_score'],
                ]
            );

            $saved++;
        }

        Notification::make()
            ->title('Berhasil')
            ->body($saved > 0
                ? 'Nilai berhasil disimpan'
                : 'Tidak ada nilai yang disimpan')
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.bulk-input-scores', [
            'students' => $this->classRoom->students()->orderBy('nama')->get(),
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }
}
