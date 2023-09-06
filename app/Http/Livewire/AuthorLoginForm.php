<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthorLoginForm extends Component
{
    public $login_id, $password;
    public $returnUrl;

    public function mount()
    {
        $this->returnUrl = request()->returnURL;
    }

    public function LoginHandler() 
    {

        //login dengan email atau username
        $fieldType = filter_var($this->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if($fieldType == 'email')
        {
            //validasi email
            $this->validate([
                'login_id' => 'required|email|exists:users,email',
                'password' => 'required|min:5' 
            ],[
                'login_id' => 'Email atau Username diperlukan ',
                'login_id.email' => 'Email tidak valid',
                'login_id.exists' => 'Email tidak terdaftar',
                'password.required' => 'Masukkan paassword'
            ]);
        } 
        else
        {
            //validasi username
            $this->validate([
                'login_id' => 'required|exists:users,username',
                'password' => 'required|min:5' 
            ],[
                'login_id' => 'Email atau Username diperlukan ',
                'login_id.exists' => 'Username tidak terdaftar',
                'password.required' => 'Masukkan paassword'
            ]);
        }

        $creds = array($fieldType=>$this->login_id,'password'=>$this->password);
        
        if(Auth::guard('web')->attempt($creds))
        {
            $chekUser = User::where($fieldType, $this->login_id)->first();
            if($chekUser->blocked == 1)
            {
                Auth::guard('web')->logout();
                return redirect()->route('author.login')->with('gagal', 'akun anda sudah terblokir');
            } 
            else
            {
                //return redirect()->route('author.home');
                if( $this->returnUrl != null ){
                    return redirect()->to($this->returnUrl);
                } else {
                    return redirect()->route('author.home');
                }
            }

        } 
        else 
        {
            session()->flash('gagal', 'email/username dan password salah');
        }

        // dd('hello login form'); //buat tes

        //login hanya email saja

        // $this->validate([
        //     'email' => 'required|email|exists:users,email',
        //     'password' => 'required|min:5'
        // ], [
        //     'email.required' => 'Masukkan alamat email anda',
        //     'email.email' => 'Alamat email tidak valid',
        //     'email.exists' => 'Alamat email tidak ditemukan',
        //     'password.required' => 'Masukkan password'
        // ]);

        // $validasi = array('email'=>$this->email, 'password'=>$this->password);

        // if(Auth::guard('web')->attempt($validasi)) {

        //     $cekUser = User::where('email', $this->email)->first();
        //     if($cekUser->blocked == 1) {
        //         Auth::guard('web')->logout();
        //         return redirect()->route('author.login')->with('gagal', 'akun anda sudah terblokir');
        //     } else{
        //         return redirect()->route('author.home');
        //     }

        // } else {
        //     session()->flash('gagal', 'email dan password salah');
        // }
    }


    public function render()
    {
        return view('livewire.author-login-form');
    }

}
