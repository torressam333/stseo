<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function addRole(Request $request)
    {
        $this->validateRequest($request);

        //Create Role
        return Role::create([
            'roleName' => $request->roleName
        ]);
    }

    public function getRole()
    {
        return Role::all();
    }

    public function editRole(Request $request)
    {
        $this->validateRequest($request);

        return Role::where('id', $request->id)->update([
            'roleName' => $request->roleName,
        ]);
    }

    public function deleteRole(Request $request)
    {
        $this->validateRequest($request);

        return Role::where('id', $request->id)->delete();
    }

    public function validateRequest(Request $request)
    {
        //Validate
        return $this->validate($request, [
            'roleName' => 'required'
        ]);
    }
}
