<?php

namespace App\Http\Controllers\Web\Admin;

require_once  __DIR__.'/../../../../../vendor/autoload.php';
use App\Broadcast;
use App\Http\Controllers\Controller;
use App\Mail\DeleteVideo;
use App\ReportBroadcast;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
// use Aws\S3;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;


class AdminBroadcastController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $inputData = $request->all();
        $data = Broadcast::with(['user'=>function($q) {
            $q->select('id','username');

        },
        'user.plugins',
        'metaInfo' => function($q){
            $q->select('meta_infos.*');
        }]);
        if (isset($inputData['username']) && $inputData['username'] != '') {
            $data = $data->whereHas('user', function (Builder $query) use($inputData) {
                $query->where('username', 'like', "%" . trim($inputData['username']) . "%");
            });
        }
        if (isset($inputData['search']) || isset($inputData['datetimes'])) {
            if (isset($inputData['search']) && $inputData['search'] != '') {
                $data = $data->where(function($query) use ($inputData){
                    $query->where('title', 'like', "%" . trim($inputData['search']) . "%");
                    $query->orWhere('share_url', 'like', "%" . trim($inputData['search']) . "%");
                });
            }
            if (isset($inputData['datetimes']) && $inputData['datetimes'] != '') {
                $datetimes = explode('-', $inputData['datetimes']);
                $datetimes[0] = str_replace('/', '-', $datetimes[0]);
                $from = $datetimes[0];
                $datetimes[1] = str_replace('/', '-', $datetimes[1]);
                $to = $datetimes[1];
                $data = $data->whereBetween('created_at', [$from, $to]);
            }
        }

        $broadcasts = $data->orderBy('created_at','desc')->paginate(20);

        // Broadcast video size start *Aleem Shaukat*
        foreach ($broadcasts as $key => $broadcast) {
            if ($broadcast->broadcast_size == 0) {
                $broadcast_size = get_video_size($broadcast);
                //
                $data_to_update = array();
                $data_to_update['broadcast_size'] = $broadcast_size;
                //
                Broadcast::whereId($broadcast->id)->update($data_to_update);
                //
                $broadcast->broadcast_size = $broadcast_size;
                $broadcast->save();
            }
        }
        // Broadcast video size end *Aleem Shaukat*


        // foreach ($broadcasts as $key => $broadcast) {
        //     $wowza_path = base_path("antmedia_store/wowza" . DIRECTORY_SEPARATOR . $broadcast->filename);
        //     if($broadcast->is_antmedia){
        //         $video_path = base_path('antmedia_store').'/'. pathinfo($broadcast->video_name, PATHINFO_FILENAME) . '.mp4';
        //         if(file_exists($video_path)) {
        //             $broadcasts[$key]['file_exists'] = true;
        //         }else{
        //             $broadcasts[$key]['file_exists'] = false;
        //         }

        //     }else{
        //         if(file_exists($wowza_path)){
        //             //$broadcst = check_file_exist($broadcast,$wowza_path);
        //             $broadcasts[$key]['file_exists'] = true;
        //             $broadcasts[$key] = $broadcast;
        //         }
        //     }
        // }
        // dd($broadcasts);
        return view('admin.all-broadcast', compact('broadcasts'));
    }
    public function deleteBroadcast(Request $request)
    {
        try {
            $broadcast_id = $request->broadcast_id;

            $user_id = Auth::user()->id;
            $broadcast = Broadcast::findOrFail($broadcast_id);

            if (isset($broadcast) && !empty($broadcast->filename)) {
                $file_path = base_path('wowza_store' . DIRECTORY_SEPARATOR . $broadcast->filename);
                if (file_exists($file_path)) {
                    if (is_file($file_path)) {
                        unlink($file_path);
                        // exec('rm -f ' . $file_path);
                    }
                }
            }
            if (isset($broadcast) && !empty($broadcast->broadcast_image)) {
                $image_file_path = public_path('images' . DIRECTORY_SEPARATOR . 'broadcasts' . DIRECTORY_SEPARATOR . $broadcast->user_id . DIRECTORY_SEPARATOR . $broadcast->broadcast_image);
                if (file_exists($image_file_path)) {
                    if (is_file($image_file_path)) {
                        unlink($image_file_path);
                        // exec('rm -f ' . $file_path);
                    }
                }
            }
            Broadcast::find($broadcast_id)->delete();
            ReportBroadcast::where('broadcast_id', $broadcast_id)->delete();

            // Send email on delete video start *Aleem Shaukat*

            $aws_item_key = 'streams/'.$broadcast->filename;

            try {
                //Create a S3Client
                $s3Client = new S3Client([
                    'region' => 'eu-west-1',
                    'version' => 'latest'
                ]);

                $result = $s3Client->deleteObject([
                    'Bucket' => 'hapitymedia',
                    'Key' => $aws_item_key
                ]);
            } catch (S3Exception $e) {
                echo $e->getMessage() . "\n";
            }
            if(empty($broadcast->title))
                $broadcast->title = 'Untitled';
            $delete_reason = str_replace("[video_title]", $broadcast->title,$request->broadcast_delete_reason);
            $broadcast_user_id = $broadcast->user_id;
            $broadcast_user = User::find($broadcast_user_id);
            $broadcast_user_name = $broadcast_user->name;
            $broadcast_user_email = $broadcast_user->email;

            $email = $broadcast_user_email;

            $data = array(
                'name' => $broadcast_user_name,
                'email' => $broadcast_user_email,
                'message' => $delete_reason,
            );
            Mail::to($email)->send(new DeleteVideo($data));
            // Mail::send(['html' => 'emails.delete_videos'], ['data' => $data], function ($message) use ($email) {
            //     $message->to($email, 'chris@hapity.com')->subject('Your Video Deleted on Hapity');
            // });
            // Send email on delete video end *Aleem Shaukat*
        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        return back()->with('flash_message', 'Broadcast Deleted Successfully ');
    }




}
