<?php

namespace App\Console\Commands;

use App\Models\Requestdb;
use App\Models\User;
use Illuminate\Console\Command;

class RequestStatusChange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'StatusChange';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change All Requests From Approved to in-progress';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
$Requests=Requestdb::all()->where('status','approved')->where('start_date',date('Y-m-d', time()));
$count=0;
foreach ($Requests as $request){
    $count++;
    $request->status='in-progress';
    $type=$request->requestable_type;
    $type=substr($type,11);
    if(!$type=='Task'){
    $user=User::find($request->user_id);
    $user->status=strtolower($type);
    $user->save();
    }
    $request->save();
    }
        return $this->info('Number of in-progress requests:'. $count);
    }
}
