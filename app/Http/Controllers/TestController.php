<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function controllerMethod()
    {
        return response()->json([
            'msg' => 'return json'
        ]);
    }

    public function test()
    {
        return response()->json([
            'msg' => 'Some Error happened'
        ], 422);
    }
}
