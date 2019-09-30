<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

    public function sendmail_contactus(Request $request){

             // $FinalMessage = "Name  = $name \r\n Email = $email \r\n Message = $message "; 
          //   $from = "webmaster.hapity@gmail.com";
          //   $this->load->library('email');
          //   $this->email->set_header('Content-Type', 'text/plain');
          //   $this->email->set_newline("\r\n");

          //   $this->email->from($from, $name);
          //   $this->email->to('masteruser@hapity.com');
          //   $this->email->cc('gohapity@gmail.com');

        $email = $request->email;
            $data = array(
                'name' => $request->name,
                'email' => $request->email,
                'message'=>$request->message
            );
            Mail::send('emails/contactus', ['data' => $data], function ($message) use ($email) {
                $message->to('fahimalyani73@gmail.com', $email)->subject('New Job Opportunity');
            });
        return "Your email has been sent successfully";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
