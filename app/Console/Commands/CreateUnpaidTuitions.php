<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Repository\StudentRepository;
use App\Repository\TuitionRepository;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class CreateUnpaidTuitions extends Command
{
    private const CHUNK_SIZE = 2;

    public function __construct(
        private StudentRepository $studentRepository,
        private TuitionRepository $tuitionRepository,
    ){
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-unpaid-tuitions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates unpaid tuitions (pending payment) for new school year';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->studentRepository->handleYearlyTuitionCreate(self::CHUNK_SIZE, [$this, 'createTuitions']);

        return 0;
    }

    public function createTuitions(Collection $students): void
    {
        $toInsert = new Collection();

        /** @var Student $student */
        foreach ($students as $student) {
            $tuition = $this->tuitionRepository->makeUnpaid($student);

            $toInsert->add($tuition);
        }

        $this->tuitionRepository->insert($toInsert);
    }
}
