<?php

namespace App\Console\Commands;

use App\Models\Requestdb;
use App\Models\Vacation;
use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Absence;
use App\Models\Salary\SalaryAdjustment;
use App\Models\Salary\SalaryAdjustmentType;
use App\Traits\Utils;
use Illuminate\Support\Facades\Auth;

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
        foreach ($absences as $absence) {
            $user = User::find($absence->user_id);
            $taken_days = $this->takendays('unscheduled', $user->id);
            $vacation_balance = $this->VacationBalance('unscheduled', $user);
            $remaining = $vacation_balance - $taken_days;
            if ($remaining > 0) {
                $vacation = new Vacation();
                $vacation->type = 'unscheduled';
                $vacation->count = 1;
                $vacation->save();
                $requestdb = new Requestdb;
                $requestdb->user_id = $user->id;
                $requestdb->start_date = $absence->date;
                $requestdb->end_date = $absence->date;
                $requestdb->status = 'finished';
                $vacation->requests()->save($requestdb);
            } else {
                if ($absence->status == 'wfh')
                    continue;
                $salarySlip = $user->lastSlip;
                $term = $user->salaryTerm;
                SalaryAdjustment::create([
                    'salary_slip_id' => $salarySlip->id,
                    'salary_adjustment_type_id' => $fullDayAbsenceAdjustment->id,
                    'amount' => $fullDayAbsenceAdjustment->percent * $term->salary_agreed,
                    'date' => $absence->date,

                ]);
            }
        }
        return 0;
    }
}
