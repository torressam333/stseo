<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogPostsController extends Controller
{
    public function uploadEditorImage(Request $request)
    {
        $localServerUrl = \config('samsValues.localServerUrl');

        $this->validate($request, [
           'image' => 'required|mimes:jpeg,jpg,png,tif,gif,eps,raw,psd,xcf,bmp'
        ]);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads'), $imageName);

        return response()->json([
            'success' => 1,
            'file' => [
                'url' => $localServerUrl . 'uploads/' . $imageName,
            ]
        ]);

        //return $imageName;
    }
}
