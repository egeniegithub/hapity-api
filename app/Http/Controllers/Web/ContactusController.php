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
            $message->to('fahimalyani73@gmail.com', $email)->subject('New Job Opportunity');
        });
        return "Your email has been sent successfully";
    }

}
