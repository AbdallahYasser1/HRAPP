<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Salary\SalarySlip;
use App\Models\Salary\SalaryAdjustmentType;
use App\Traits\CalcNetSalary;

class GenerateSlips extends Command
{
    use CalcNetSalary;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:slips';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slips for all users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            $slip = new SalarySlip();
            $slip->user_id = $user->id;
            $slip->salary_term_id = $user->salaryTerm->id;
            $slip->date = date('Y-m-d');
            $slip->save();

            $adjustmentTypes = SalaryAdjustmentType::all();
            foreach ($adjustmentTypes as $adjustmentType) {
                if($adjustmentType->isAll) {
                    $slip->adjustments()->create([
                        'salary_adjustment_type_id' => $adjustmentType->id,
                        'amount' => $adjustmentType->amount,
                        'percent' => $adjustmentType->percent,
                        'date' => date('Y-m-d'),
                    ]);
                }
            }
            $this->calculateSlip($slip);
        }

        return 0;
    }
}
