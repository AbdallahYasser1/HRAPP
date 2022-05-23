<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Absence;
use App\Models\Salary\SalaryAdjustment;
use App\Models\Salary\SalaryAdjustmentType;
use App\Traits\Utils;

class DeductAttendanceDeduction extends Command
{
    use Utils;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deduct:absence';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deduct absence deduction';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $absences = Absence::where('date', '=', date('Y-m-d'))->get();
        $salaryAdjustmentTypes = new SalaryAdjustmentType;
        $fullDayAbsenceAdjustment = $salaryAdjustmentTypes->getFullDayAbsenceAdjustment();
        foreach($absences as $absence) {
            $user = User::find($absence->user_id);
            $salarySlip = $user->lastSlip;
            $term = $user->salaryTerm;
            SalaryAdjustment::create([
                'salary_slip_id' => $salarySlip->id,
                'salary_adjustment_type_id' => $fullDayAbsenceAdjustment->id,
                'amount' => $fullDayAbsenceAdjustment->percent * $term->salary_agreed,
                'date' => $absence->date,

            ]);
        }
        return 0;
    }
}
