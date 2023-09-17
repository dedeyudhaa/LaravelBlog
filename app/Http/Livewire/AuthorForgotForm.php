<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthorForgotForm extends Component
{

    public $email;
    
    public function ForgotHandler()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Atribute dibutuhkan',
            'email.email' => 'Alamat email invalid',
            'email.exists' => 'Alamat email tidak terdaftar'
        ]);

        if(DB::table('password_reset_tokens')->where('email', '=', $this->email)->exists()) 
        {
            $token = base64_encode(Str::random(64));
            
            DB::table('password_reset_tokens')->update([
                'token'=>$token,
                'created_at'=>Carbon::now()
            ]);
        } 
        else {

            $token = base64_encode(Str::random(64));
            
            DB::table('password_reset_tokens')->insert([
                'email'=>$this->email,
                'token'=>$token,
                'created_at'=>Carbon::now()
            ]);
        }

        $user = User::where('email', $this->email)->first();
        $link = route('author.reset-form', ['token'=>$token, 'email'=>$this->email]);
        $body_message = "Kami menerima permintaan reset password untuk akun ".$this->email.". <br> Silahkan klik tombol dibawah </br>.";
        $body_message.= '<br>';
        $body_message.= '<a href="'.$link.'" target="_blank" style="font: bold 11px Arial;text-decoration: none; background-color: #EEEEEE; color: #333333; padding: 2px 6px 2px 6px;border-top: 1px solid #CCCCCC; border-right: 1px solid #333333; border-bottom: 1px solid #333333; border-left: 1px solid #CCCCCC;"> Reset Password </a>';
        $body_message.= '<br>';
        $body_message.= 'Jika anda tidak meminta reset password silahkan abaikan pesan ini';

        $data = array(
            'name'=> $user->name,
            'body_message'=>$body_message
        );

        // Mail::send('forgot-email-template', $data, function($message) use ($user){
        //     $message->from('noreply@gmail.com', 'Admin');
        //     $message->to($user->email, $user->name)->subject('Reset Password');
        // });

        //PHP MAILER
        
        $mail_body = view('forgot-email-templat', $data)->render();

        $mailConfig = array(
            'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
            'mail_from_name' => env('EMAIL_FROM_NAME'),
            'mail_recipient_email' => $user->email,
            'mail_recipient_name' => $user->name,
            'mail_subject' => 'Reset Password',
            'mail_body' => $mail_body
        );

        sendMail($mailConfig);

        $this->email = null;
        session()->flash('success','Silahkan periksa email anda');

    }

    public function render()
    {
        return view('livewire.author-forgot-form');
    }
}
