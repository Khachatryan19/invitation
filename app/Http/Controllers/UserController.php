<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $path = $this->fileDownload($request->file);

        $users = (new FastExcel)->import($path);

        $users->map(function ($user) {
            $this->createUser($user);
        });

        Http::get('http://localhost:3001/getAll');
    }

    public function createUser(array $userData)
    {
        $validator = Validator::make($userData, [
            'email' => 'unique:users'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->first('email');
        }
        $user = new User();

        $user->name = $userData['name'];
        $user->surname = $userData['surname'];
        $user->email = $userData['email'];
        $user->password = bcrypt(Str::random(8));
        $user->save();

        $this->fillResetPassword($user);

    }

    public function sendInvitation(array $userData)
    {
        $details = [
            'title' => 'Mail from mailtrap',
            'body'  => "
                       Congratulations {$userData['name']}!!!,
                       You are registered in our web-site,
                       if you want to change your password 
                       click the link below
                      "
        ];

        Mail::to($userData['email'])->send(new TestMail($details));
    }

    public function fillResetPassword(User $user)
    {
        $passwordReset = new PasswordReset();

        $passwordReset->email = $user->email;
        $passwordReset->token = Str::uuid();

        $passwordReset->save();
    }

    public function fileDownload(UploadedFile $file)
    {
        $path = Storage::path('users.ods');
        $file->storeAs(null, 'users.ods');

        return $path;
    }

    public function getAll() : JsonResponse
    {
        return new JsonResponse(
            PasswordReset::all([
                'email',
                'token'
            ])
        );
    }
}
