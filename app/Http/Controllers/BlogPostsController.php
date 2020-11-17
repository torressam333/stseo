<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Blogcategory;
use App\Blogtag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class BlogPostsController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
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

    public function slug(Request $request)
    {
        return Blog::create([
            'title' => $request->title,
            'post' => $request->post,
            'postExcerpt' => $request->postExcerpt,
            'slug' => $request->title,
            'user_id' => Auth::user()->getKey(),
            'metaDescription' => $request->metaDescription,
        ]);
    }

    public function createBlog(Request $request)
    {
        $this->validateBlog($request);

        $categories = $request->category_id;
        $tags = $request->tag_id;
        $blogCategories = [];
        $blogTags = [];

        DB::beginTransaction();

        try {
            //Create blog first
            $blog = Blog::create([
                'title' => $request->title,
                'post' => $request->post,
                'postExcerpt' => $request->postExcerpt,
                'slug' => $request->title,
                'user_id' => Auth::user()->id,
                'metaDescription' => $request->metaDescription,
                'jsonData' => $request->jsonData,
            ]);

            //Create blog category
            foreach ($categories as $category) {
                array_push($blogCategories,
                    [
                        'category_id' => $category,
                        'blog_id' => $blog->id,
                    ]);
            }

            foreach ($tags as $tag) {
                array_push($blogTags,
                    [
                        'tag_id' => $tag,
                        'blog_id' => $blog->id,
                    ]);
            }

            Blogcategory::insert($blogCategories);
            Blogtag::insert($blogTags);

            DB::commit();
            return "Success!";

        } catch (Throwable $throwable) {
            DB::rollBack();
            return "Error: " . $throwable;
        }
    }

    public function getBlogData()
    {
        return Blog::with(['category', 'tag'])->orderBy('id', 'desc')->get();
    }

    public function deleteBlog(Request $request)
    {
        return Blog::where('id', $request->id)->delete();
    }

    public function getSingleBlog(Request $request, $id)
    {
        return Blog::with(['category', 'tag'])->firstWhere('id', $id);
    }

    public function updateBlog(Request $request, $id)
    {
        $this->validateBlog($request);

        $categories = $request->category_id;
        $tags = $request->tag_id;
        $blogCategories = [];
        $blogTags = [];

        DB::beginTransaction();

        try {
            //Create blog first
            $blog = Blog::where('id', $id)->update([
                'title' => $request->title,
                'post' => $request->post,
                'postExcerpt' => $request->postExcerpt,
                'slug' => $request->title,
                'user_id' => Auth::user()->id,
                'metaDescription' => $request->metaDescription,
                'jsonData' => $request->jsonData,
            ]);

            //Remove previous values for cats and tags
            Blogcategory::where('blog_id', $id)->delete();
            Blogcategory::insert($blogCategories);

            //Remove previous values for cats and tags
            Blogtag::where('blog_id', $id)->delete();
            Blogtag::insert($blogTags);

            //Create blog category
            foreach ($categories as $category) {
                array_push($blogCategories,
                    [
                        'category_id' => $category,
                        'blog_id' => $id,
                    ]);
            }

            foreach ($tags as $tag) {
                array_push($blogTags,
                    [
                        'tag_id' => $tag,
                        'blog_id' => $id,
                    ]);
            }

            Blogcategory::insert($blogCategories);
            Blogtag::insert($blogTags);

            DB::commit();
            return "Success!";

        } catch (Throwable $throwable) {
            DB::rollBack();
            return "Error: " . $throwable;
        }
    }

    public function validateBlog(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:2',
            'post' => 'required|min:3',
            'postExcerpt' => 'required',
            'metaDescription' => 'required',
            'jsonData' => 'required',
            'category_id' => 'required',
            'tag_id' => 'required',
        ]);
    }
}
