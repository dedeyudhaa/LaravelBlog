<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;
use Illuminate\Support\Facades\Mail;
use Livewire\WithPagination;
use Illuminate\Support\Facades\File;

class Authors extends Component
{
    use WithPagination;
    public $name, $email, $username, $author_type, $direct_publisher;
    public $search;
    public $perPage = 8; //jumlah author yang ditampilkan 
    public $selected_author_id;
    public $blocked = 0;

    protected $listeners = [
        'resetForms',
        'deleteAuthorAction'
    ];

    // untuk mereset pagination ketika halaman di refresh
    public function mount()
    {
        $this->resetPage();
    }

    //mereset halaman ketika melakukan pencarian
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetForms()
    {
        $this->name = $this->email = $this->username = $this->author_type = $this->direct_publisher = null;
        $this->resetErrorBag();
    }

    public function addAuthor()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username|min:6|max:20',
            'author_type' => 'required',
            'direct_publisher' => 'required'
        ], [
            'author_type.required'=>'Pilih tipe author',
            'direct_publisher.required' => 'Spesifikkan akses publikasi author'
        ]);

        if($this->isOnline()){
            
            
            $default_password = Random::generate(8);

            $author = new User();
            $author->name = $this->name;
            $author->email = $this->email;
            $author->username = $this->username;
            $author->password = Hash::make($default_password);
            $author->type = $this->author_type;
            $author->direct_publish = $this->direct_publisher;
            $saved = $author->save();


            $data = array(
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'password' => $default_password, 
                'url' => route('author.profile'),
            );

            $author_email = $this->email;
            $author_name = $this->name;

            if($saved) {

                // Mail::send('new-author-email-template', $data, function($message) use ($author_email, $author_name){
                //     $message->from('noreply@example.com', 'LaravelBlog');
                //     $message->to($author_email,$author_name)
                //     ->subject('Pembuatan Akun');
                // });

                //PHP MAILER

                $mail_body = view('new-author-email-template', $data)->render();

                $mailConfig = array(
                    'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                    'mail_from_name' => env('EMAIL_FROM_NAME'),
                    'mail_recipient_email' => $author->email,
                    'mail_recipient_name' => $author->name,
                    'mail_subject' => 'Reset Password',
                    'mail_body' => $mail_body
                );
        
                sendMail($mailConfig);
                
                $this->showToaster('Author baru berhasil ditambahkan', 'success');
                $this->name = $this->email = $this->username = $this->author_type = $this->direct_publisher = null;
                $this->dispatchBrowserEvent('hide_add_author_modal');

            } else{
                $this->showToaster('Terjadi Kesalahan', 'error');
            }


        } else{
            $this->showToaster('Anda sedang offline, perikasa koneksi internet anda dan ulangi submit form setelahnya', 'error');
        }
    }

    // fungsi untuk edit author
    public function editAuthor($author)
    {
        // dd(['open edit author modal', $author]);

        //menampilkan data author di input text
        $this->selected_author_id = $author['id'];
        $this->name = $author['name'];
        $this->email = $author['email'];
        $this->username = $author['username'];
        $this->author_type = $author['type'];
        $this->direct_publisher = $author['direct_publish'];
        $this->blocked = $author['blocked'];
        
        // menampilkan modal 
        $this->dispatchBrowserEvent('showEditAuthorModal');
    }

    //fungsi untuk update data author/user
    public function updateAuthor()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$this->selected_author_id,
            'username' => 'required|min:6|max:20|unique:users,username,'.$this->selected_author_id
        ]);

        if($this->selected_author_id){
            $author = User::find($this->selected_author_id);
            $author->update([
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'type' => $this->author_type,
                'blocked' => $this->blocked,
                'direct_publish' => $this->direct_publisher
            ]);

            $this->showToaster('Data author berhasil diperbarui','success');
            $this->dispatchBrowserEvent('hide_edit_auhtor_modal');
        }
    }


    //FUNGSI HAPUS DATA AUTHOR
    public function deleteAuthor($author)
    {
        //dd('delete author : ',$author);
        $this->dispatchBrowserEvent('deleteAuthor',
        [
            'title' => 'Apa anda yakin ?',
            'html' => 'Anda ingin menghapus data author ini: <br><b>'.$author['name'].'</b>',
            'id' => $author['id']
        ]);
    }

    public function deleteAuthorAction($id)
    {
        // dd('yes hapus');
        $author = User::find($id);
        $path = 'back/dist/img/authors/';
        $author_picture = $author->getAttributes()['picture'];

        //dd($author_picture);

        $picture_full_path = $path.$author_picture;
        if($author_picture != null || File::exists(public_path($picture_full_path))){
            File::delete(public_path($picture_full_path));
        }

        $author->delete();
        $this->showToaster('Author berhasil dihapus.', 'info');
    }

    public function showToaster($message, $type){
        return $this->dispatchBrowserEvent('showToaster', [
            'type' => $type,
            'message' => $message
        ]);
    }

    public function isOnline($site = "https://youtube.com/")
    {
        if(@fopen($site,"r")){
            return true;
        } else {
            return false;
        }
    }

    public function render()
    {
        return view('livewire.authors', [
            // menampilkan data user author yang tidak sama dengan id yg login
            'authors' => User::search(trim($this->search))->where('id', '!=', auth()->id())->paginate($this->perPage)
        ]);
    }
}
