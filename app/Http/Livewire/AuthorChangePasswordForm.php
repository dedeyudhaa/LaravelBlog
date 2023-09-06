<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthorChangePasswordForm extends Component
{

    public $current_password, $new_password, $confirm_new_password;

    public function changePassword()
    {
        $this->validate([
            'current_password' => [
                'required', function($attribute, $value, $fail){
                    if(!Hash::check($value, User::find(auth('web')->id())->password)) 
                    {
                        return $fail(__('Password anda  salah'));
                    }
                },
            ],
            
            'new_password' => 'required|min:5|max:25',
            'confirm_new_password' => 'same:new_password'
        ],[
            'current_password.required' => 'Masukkan password anda',
            'new_password.required' => 'Masukkan password baru',
            'confirm_new_password.same' => 'konfirmasi harus sama dengan password baru'
        ]);

        $query = User::find(auth('web')->id())->update([
            'password' => Hash::make($this->new_password)
        ]);

        if($query) {
            $this->showToaster('Ganti password berhasil', 'success');
            $this->current_password = $this->new_password = $this->confirm_new_password = null;
        } else{
            $this->showToaster('terjadi Kesalahan', 'error');
        }
    }

    public function showToaster($message, $type){
        return $this->dispatchBrowserEvent('showToaster', [
            'type' => $type,
            'message' => $message
        ]);
    }

    public function render()
    {
        return view('livewire.author-change-password-form');
    }
}
