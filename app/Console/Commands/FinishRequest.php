<?php

namespace App\Console\Commands;

use App\Models\Requestdb;
use App\Models\User;
use Illuminate\Console\Command;

class FinishRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finishrequest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change request status from in-progress to finished ';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $Requests=Requestdb::all()->where('status','in-progress')->where('end_date',date('Y-m-d', time()));
        $count=0;
        foreach ($Requests as $request){
            $count++;
            $request->status='finished';
            $type=$request->requestable_type;
            $type=substr($type,11);
            if($type!='Task'){
                $user=User::find($request->user_id);
                    $user->status='available';
                $user->save();
            }
            $request->save();
        }
        return $this->info('Number of finished requests:'. $count);
    }
}
