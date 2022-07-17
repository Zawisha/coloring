<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Nette\Schema\ValidationException;

class ProfileController extends Controller
{
    public function change_profile_data(Request $request)
    {
        $user = Auth::user();
        $email = $request->input('email');
        $nickname = $request->input('nickname');
        try {
        $this->validate($request,[
            'email'=> 'required|string|min:5|max:60|email|unique:users,email,'.$user->id.',id',
            'nickname'=> 'required|string|min:3|max:60|unique:users,nickname,'.$user->id.',id',
                  ]);
        }
        catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }
        User::where('id','=',$user->id)->
        update([
            'email' => $email,
            'nickname' => $nickname,
        ]);
        return response()->json([
            'status' => 'success',
            'message'    => 'Профиль обновлён',
        ], 201);
    }
    public function logout()
    {
        auth()->logout();
    }
    public function changePasswordPost(Request $request)
    {
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Вы ввели не верный старый пароль");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            // Current password and new password same
            return redirect()->back()->with("error","Новый пароль не может быть таким же как и предыдущий");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with("success","Пароль успешно изменён");

    }
    public function upload_crop(Request $request)
    {
        $img = $request->input('img');
        $user = Auth::user();
        $folderPath = public_path()."/images/ava/";
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
//        $file = $folderPath .  $user->id . '. '.$image_type;
        $time=time();
        $file = $folderPath .  $user->id .$time.'.'.$image_type;
        file_put_contents($file, $image_base64);
        User::where('id','=',$user->id)->
        update([
            'ava' => $user->id .$time.'.'.$image_type,
        ]);
        return response()->json([
            'status' => 'success',
            'message'    => 'Аватар сохранён',
        ], 201);
    }
    public function get_user_params()
    {
        $user = Auth::user();
        $ava = User::where('id', $user->id)
            ->get('ava');
        return response()->json([
            'status' => 'success',
            'ava'    => $ava,
        ], 201);
    }
    public function get_user_params_main()
    {
        $user = Auth::user();
        $ava = User::where('id', $user->id)
            ->get();
        return response()->json([
            'status' => 'success',
            'ava'    => $ava,
        ], 201);
    }
}
