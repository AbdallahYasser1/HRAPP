<?php

namespace App\Console\Commands;
use App\Models\Requestdb;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MakeRequestCanceledCommand extends Command
{
    protected $signature = 'makerequestcanceled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make all pending requests after 1 day from sending and it pending status canceled';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = date('Y/m/d',strtotime("-1 days"));
        $pendingRequests=Requestdb::where('status','pending')->where('start_date',$date)->update(['status'=>'canceled']);
        $this->info("Number of Request canceled: " . $pendingRequests);
    }
}
