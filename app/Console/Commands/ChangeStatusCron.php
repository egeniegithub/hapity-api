<?php

namespace App\Console\Commands;

use App\Broadcast;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ChangeStatusCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changestatus:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        Log::info("Cron is working fine!");
     
        $broadcast = Broadcast::select('id','status','timestamp')->where('status','online')->get();
        $cur_time=date("Y-m-d H:i:s");
        $duration='+30 seconds';
        $checkDateTime = date('Y-m-d H:i:s', strtotime($duration, strtotime($cur_time)));

        if (!empty($broadcast) && !is_null($broadcast)) {
            foreach ($broadcast as $key => $value) {
                if (!is_null($value->timestamp)) {
                    if ($value->timestamp < $checkDateTime) {

                        $broadcasts = Broadcast::find($value->id);
                        $broadcasts->status = 'offline';
                        $broadcasts->save();
    
                    } 
                } else {
                    $broadcasts = Broadcast::find($value->id);
                    $broadcasts->status = 'offline';
                    $broadcasts->save();
                }
                
            }
        } else {

            $this->info('Status:Cron Command Run successfully but record not found !');
        }
      
        $this->info('Status:Cron Command Run successfully!');
    }
}
