<?php

namespace App\Console\Commands;

use App\Broadcast;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class StopVoDRecording extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stop_vod_rec';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limit Video recording after sometime';

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
        $vod_limit = env('VOD_REC_LIMIT');
        $minutes = $vod_limit+30;
        $date = new \DateTime();
        $date->modify('-'.$minutes.' minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');

        $date2 = new \DateTime();
        $date2->modify('-'.$vod_limit.' minutes');
        $formatted_date2 = $date2->format('Y-m-d H:i:s');

        $broadcasts = Broadcast::where('created_at','>=',$formatted_date)
            ->where('created_at','<',$formatted_date2)
            ->where('status','online')
            ->get();
        foreach($broadcasts as $broadcast){
            $client = new Client();
            $stream_key = str_replace("_720p.mp4","",$broadcast->video_name);
            $url = ANT_MEDIA_SERVER_STAGING_URL.WEBRTC_APP."/rest/v2/broadcasts/".$stream_key."/stop";
            echo $url;exit;
            $resp = $client->request('PUT',$url);
            print_r($resp->getBody());
        }
    }
}
