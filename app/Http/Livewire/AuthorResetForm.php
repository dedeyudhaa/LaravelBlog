<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthorResetForm extends Component
{

    public $email, $token, $new_password, $confirm_new_password;

    public function mount()
    {
        $this->email = request()->email;
        $this->token = request()->token;
    }

    public function ResetHandler()
    {
        // dd('reset now');

        $this->validate([
            'email' => 'required|email|exists:users,email',
            'new_password' => 'required|min:5',
            'confirm_new_password' => 'same:new_password'
        ],
        [
            'email.required' => 'Email diperlukan',
            'email.email' => 'Email tidak valid',
            'email.exists' => 'Email tidak terdaftar',
            'new_password.required' => 'Masukkan password baru',
            'new_password.min' => 'Minimal 5 karakter',
            'confirm_new_password' => 'Password baru harus sama dengan konfirmasi password'        
        ]);

        $check_token = DB::table('password_reset_tokens')->where([
            'email' => $this->email,
            'token' => $this->token
        ])->first();

        if(!$check_token)
        {
            session()->flash('fail', 'Token tidak valid');
        } else {
            User::where('email', $this->email)->update([
                'password' => Hash::make($this->new_password)
            ]);

            DB::table('password_reset_tokens')->where([
                'email' => $this->email
            ])->delete();

            $success_token = Str::random(64);
            session()->flash('success', 'Password telah berhasil diperbarui. Silahkan login dengan email dan password baru anda');

            $this->redirectRoute('author.login',[
                'tkn' => $success_token, 
                'UEmail' => $this->email
            ]);
        }
    }


    public function render()
    {
        return view('livewire.author-reset-form');
    }
}
