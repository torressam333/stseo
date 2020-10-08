<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RolePermissionsController extends Controller
{
    public function assignRolePermission(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'permission' => 'required'
        ]);

        return Role::where('id', $request->id)->update([
           'permission' => $request->permission
        ]);
    }
}
