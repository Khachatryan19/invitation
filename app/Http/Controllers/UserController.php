<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class UserController extends Controller
{
    public function invite(Request $request)
    {
        $path =  $this->fileDownload($request->file);

        $users = (new FastExcel)->import($path);

        $users->map(function ($user){
            $this->createUser($user);
        });
    }

    public function createUser(array $userData)
    {
        $user = new User();

        $user->name = $userData['name'];
        $user->surname = $userData['surname'];
        $user->email = $userData['email'];
        $user->password = bcrypt(Str::random(8));

        $user->save();

        $this->sendInvitation($userData);
    }

    public function sendInvitation(array $userData)
    {
        $details = [
            'title' => 'Mail from mailtrap',
            'body' => "
                       Congratulations {$userData['name']}!!!,
                       You are registered in our web-site,
                       if you want to change your password 
                       click the link below
                      "
        ];

        Mail::to($userData['email'])->send(new \App\Mail\TestMail($details));
    }

    public function fileDownload(UploadedFile $file)
    {
        $path = Storage::path('users.ods');
        $file->storeAs(null,'users.ods');

        return $path;
    }
}
