<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

    public function index(Request $request)
    {
        $user = Auth::user();
        $authCheck = Auth::check();

        if (!$authCheck && $request->path() !== 'login') {
            return redirect('/login');
        } elseif (!$authCheck && $request->path() === 'login') {
            return view('welcome');
        }
        //Non-admins
        if ($user->role->isAdmin === 0) {
            return redirect('/login');
        }

        if ($request->path() === 'login'){
            return redirect('/');
        }

        return $this->checkPermissions($user, $request);
    }

    public function checkPermissions($user, Request $request)
    {
        //Get logged in user permissions
        $permissions = json_decode($user->role->permission);

        //Check for read permission
        $hasPermission = false;

        if (!$permissions) {
            return view('notfound');
        }

        foreach ($permissions as $permission){
            if ($permission->name === $request->path()) {
                if ($permission->read) {
                    $hasPermission = true;
                }
            }
        }

        if($hasPermission) return view('welcome');

        return view('notfound');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function validateUsers(Request $request)
    {
        //Validate the request
        return $this->validate($request, [
            'fullName' => 'required|min:2',
            'email' => 'bail|required|email|unique:users|max:255',
            'password' => 'bail|required|min:6',
            'role_id' => 'required'
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
            'role_id' => $request->role_id
        ]);
    }

    public function getUser()
    {
        //Return all users who are not "users"
        return User::where('role_id', '!=', 4)->get();
    }

    public function editUser(Request $request)
    {
        $this->validate($request, [
            'fullName' => 'required|min:2',
            'email' => "bail|required|email|unique:users,email,$request->id",
            'password' => 'min:6',
            'role_id' => 'required'
        ]);

        $data = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'role_id' => $request->role_id
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

    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6'
        ]);

        //Login
        if (Auth::attempt(
            [
                'email' => $request->email,
                'password' => $request->password
            ]
        )) {
            //Get authenticated user
            $user = Auth::user();

            //Not an admin
            if($user->role->isAdmin === 0){
                Auth::logout();
                return response()->json([
                    'msg' => 'You do not have admin permissions'
                ], 401);
            }
            return response()->json([
               'msg' => 'You are logged in',
                'user' => $user,
            ]);
        }else{
            return response()->json([
                'msg' => 'Incorrect login credentials'
            ], 401);
        }
    }

    public function deleteUser(Request $request)
    {
        return User::where('id', $request->id)->delete();
    }
}
