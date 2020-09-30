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

    public function validateUsers(Request $request)
    {
        //Validate the request
        return $this->validate($request, [
            'fullName' => 'required|min:2',
            'email' => 'bail|required|email|unique:users|max:255',
            'password' => 'bail|required|min:6',
            'userType' => 'required'
        ]);
    }


    public function createUser(Request $request)
    {
        $this->validateUsers($request);

        //Encrypyt password
        $password = bcrypt($request->password);

        return User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => $password,
            'userType' => $request->userType
        ]);
    }

    public function getUser()
    {
        return User::where('userType', '!=', 'User')->get();
    }

    public function editUser(Request $request)
    {
        $this->validate($request, [
            'fullName' => 'required|min:2',
            'email' => "bail|required|email|unique:users,email,$request->id",
            'password' => 'min:6',
            'userType' => 'required'
        ]);

        $data = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'userType' => $request->userType
        ];

        //If user us updating their current password
        if ($request->password) {
            //Encrypt password
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }

        //Return if user changes data
        return User::where('id', $request->id)->update($data);
    }
}
