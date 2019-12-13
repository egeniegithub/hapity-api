<?php

namespace App\Console\Commands;

use App\Broadcast;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BroadcastUpdateLiveStatusCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'broadcastupdatelivestatus:cron';

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

        $date = new DateTime();
        $date->modify('-1 minute');

        $null_case_date = new DateTime();
        $null_case_date->modify('-5 minute');

        $broadcasts = Broadcast::select('id', 'status', 'timestamp')->where('status', 'online')->get();

        if ($broadcasts->count() > 0) {
            foreach ($broadcasts as $key => $broadcast) {
                // Log::info(json_encode($broadcasts->toArray()));

                $broadcast_timestamp = !is_null($broadcast->timestamp) && !empty($broadcast->timestamp)
                ? DateTime::createFromFormat('Y-m-d H:i:s', $broadcast->timestamp)
                : $null_case_date;

                $broadcast_unix = $broadcast_timestamp->getTimeStamp();
                $check_unix = $date->getTimeStamp();

                // Log::info($broadcast_unix . ' - ' . $check_unix);

                if ($broadcast_unix < $check_unix) {
                    $broadcast->status = 'offline';
                    $broadcast->save();
                }

            }
        } else {

            $this->info('Status:Cron Command Run successfully but record not found !');
        }

        $this->info('Status:Cron Command Run successfully!');
    }
}
