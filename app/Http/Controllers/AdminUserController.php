<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Class AdminUserController
 * @package App\Http\Controllers
 */
class AdminUserController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     * @throws ValidationException
     */


    public function createUser(Request $request)
    {
        //Validate the request
        $this->validate($request, [
            'fullName' => 'required|min:2',
            'email' => 'bail|required|email|unique:users|max:255',
            'password' => 'bail|required|min:6',
            'userType' => 'required'
        ]);

        //Encrypyt password
        $password = bcrypt($request->password);

        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => $password,
            'userType' => $request->userType
        ]);

        return $user;
    }

    public function getUser()
    {
        return User::where('userType', '!=', 'User')->get();
    }
}
