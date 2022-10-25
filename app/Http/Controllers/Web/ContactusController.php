<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('about');
    }

    public function sendmail_contactus(Request $request)
    {
        $validator = \validator::make(request()->all(), [
            'name'                  =>  'required',
            'email'                 =>  'required',
            'message'               =>  'required',
            'g-recaptcha-response'  => 'recaptcha',
        ]);

        // check if validator fails
        if($validator->fails()) {
            $errors = $validator->errors();
            return back()->with('errors',$errors)
                        ->withInput($request->all());
        }

        if ($request->check == 'on'){
            return back()->with('flash_message','Email Send Successfully ');
        }

        $email = $request->email;

        $data = array(
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        );
        Mail::send('emails/contactus', ['data' => $data], function ($message) use ($email) {
            $message->to(CONTACTUS_SEND_TO_EMAIL, $email)->subject('Contact Us');
        });
        return back()->with('flash_message','Email sent Successfully ');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function shutDownNotification()
    {
        $date = Carbon::now()->subYears(2);
        $users = User::leftjoin('broadcasts as b', 'b.user_id', 'users.id')
            ->where('b.timestamp', '>=', $date)
            ->groupBy('email')
            ->orderBy('b.timestamp', 'Desc')
            ->pluck('email')->toArray();
        dd($users);
        Mail::send('emails/shut_down_notification', ['data' => 'Registration closed'], function ($message) {
            $message->to('shakoorha@gmail.com')->subject('Important! Shutdown Notification');
        });
        foreach ($users as $email) {
            Mail::send('emails/shut_down_notification', ['data' => 'Registration closed'], function ($message) use ($email) {
                $message->to($email)->subject('Important! Shutdown Notification');
            });
        }
        return back()->with('flash_message','Email sent Successfully ');
    }
}
