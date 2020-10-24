<?php

namespace App\Http\Controllers;

use App\Blog;
use Illuminate\Http\Request;

class BlogPostsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
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
    }

    public function slug()
    {
        return Blog::create([
            'title' => 'This is a nice title',
            'post' => 'some post',
            'postExcerpt' => 'some post here',
            'user_id' => 11,
            'metaDescription' => 'some meta info here',
        ]);
    }
}
