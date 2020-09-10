<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function addTag(Request $request)
    {
        //Validate
        return Tag::create([
            'tagName' => $request->tagName,
        ]);
    }
}
