<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;

class SettingController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function settings()
	{
        $userinfo = User::with('profile')->where('id', Auth::id())->first()->toArray();
    	return view('setting',compact('userinfo'));     
	}
    public function save_settings(Request $request){
        dd($request->all());
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = time().'.'.$extension;
            $path = public_path('images/');
            $file->move($path, $filename);
            $image_name = $filename;
        }

            $type = addslashes($this->input->post('type'));
            $user_id = addslashes($this->input->post('user_id'));
            
            if($type=='account'){
                $username = addslashes($this->input->post('username'));
                $email = addslashes($this->input->post('email'));
                $picture_change = $this->input->post('picture_change');
                $profile_picture = $this->input->post('profile_picture');
                $is_sensitive = $this->input->post('is_sensitive');
  
                define('UPLOAD_URL', 'https://api.hapity.com/uploads/user_profile_pictures/');
                if($picture_change=='true'){
                    $picture_path = $this->saveImage($profile_picture);
                    $picture_path = /*UPLOAD_URL.*/$picture_path;
                }
                else{
                    $picture_path = $profile_picture;
                }
                $data = array(
                    'username'=>$username,
                    'email'=>$email,   
                    'profile_picture'=>$picture_path,
                    'is_sensitive'  => $is_sensitive
                );
                $this->db->update('user', $data, "sid = $user_id");
            }
            else if($type=='privacy'){
                $password = md5($this->input->post('password'));
                $data = array(
                    'password'=>$password,
                );
                $this->db->update('user', $data, "sid = $user_id");
            }
            echo 'success';
        }
}
