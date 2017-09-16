<?php
namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Mail;
class PagesController extends Controller {
        public function getIndex()
        {
            $posts=Post::orderBy('created_at','desc')->limit(4)->get();
            return view('pages.welcome',['posts'=>$posts]);
        }
        public function getAbout()
        {
            $first='Alex';
            $last='Curtis';
            $full=$first . " " . $last;
            $email = 'alex@gmail.com';
            return view('pages.about',['fullname'=>$full,'email'=>$email]);
        }
        public function getContact()
        {
            return view('pages.contact');
        }

        public function postContact(Request $request)
        {
            $this->validate($request, [
                'email' => 'required|email',
                'subject' => 'min:3',
                'message' => 'min:10'

            ]);
            $data = array(
                'email' => $request->email,
                'subject' => $request->subject,
                'bodyMessage'=>$request->message
            );

            Mail::send('emails.contact',$data, function($message) use($data)
            {
                $message->from($data['email']);
                $message->to('iminokhunov@mailtrap.io');
                $message->subject($data['subject']);
            });
            Session::flash('success','Your email was sent successfully');
            return redirect()->route('home');
        }

    }