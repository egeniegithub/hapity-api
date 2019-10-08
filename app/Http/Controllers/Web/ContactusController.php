<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;

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
        $email = $request->email;
        $data = array(
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        );
        Mail::send('emails/contactus', ['data' => $data], function ($message) use ($email) {
            $message->to(CONTACTUS_SEND_TO_EMAIL, $email)->subject('New Job Opportunity');
        });
        return back()->with('flash_message','Email Send Successful;ly ');
        return "Your email has been sent successfully";
    }

}
