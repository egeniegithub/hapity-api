<?php

namespace App\Http\Controllers\Web;

use App\Broadcast;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $post_share_url = '';
        $type = '';
        $userdata = User::with(['profile', 'plugins'])->where('id', Auth::id())->first()->toArray();
        $broadcasts = Broadcast::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        if(!is_null($userdata['plugins'])){
            if(!is_null($userdata['plugins']['0']['url'])){
                $url = parse_url($userdata['plugins']['0']['url']);
                $post_share_url = $url['scheme'].'://'.$url['host'];
                $type = $userdata['plugins']['0']['type'];
            }
        }

        return view('home', compact('userdata', 'broadcasts','post_share_url','type'));
    }

}
