<?php

namespace App\Http\Controllers;

use App\Category;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Class AdminController
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     * @throws ValidationException
     */
    public function addTag(Request $request)
    {
        //Validate the request
        $this->validate($request, [
            'tagName' => 'required'
        ]);
        return Tag::create([
            'tagName' => $request->tagName,
        ]);
    }

    public function editTag(Request $request)
    {
        //Validate the request
        $this->validate($request, [
            'tagName' => 'required',
            'id' => 'required'
        ]);

        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName,
        ]);
    }

    public function deleteTag(Request $request)
    {
        //Validate the request
        $this->validate($request, [
            'id' => 'required'
        ]);

        return Tag::where('id', $request->id)->delete();
    }

    public function getTag()
    {
        return Tag::orderBy('id', 'desc')->get();
    }

    public function upload(Request $request)
    {
        $this->validate($request, [
           'file' => 'required|mimes:jpeg,jpg,bmp,png,doc,docx,txt,xlsx,xlsm,pdf'
        ]);
        //Get file extension and upload to public/uploads file
        $picName = time() . '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $picName);

        return $picName;
    }

    public function deleteImage(Request $request)
    {
        $fileName = $request->imageName;
        $this->deleteFileFromServer($fileName);
        return 'Deleted File';
    }

    public function deleteFileFromServer($fileName)
    {
        $filePath = public_path(). '/uploads/'. $fileName;
        file_exists($filePath) ? @unlink($filePath) : null;
    }

    public function addCategory(Request $request)
    {
        //Validate the request
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);
        return Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,
        ]);
    }

    public function getCategories()
    {
        return Category::orderBy('id', 'desc')->get();
    }

    public function deleteCategory(Request $request)
    {
        //Validate the request
        $this->validate($request, [
            'id' => 'required'
        ]);

        return Category::where('id', $request->id)->delete();
    }

    public function editCategory(Request $request)
    {
        //Validate the request
        $this->validate($request, [
            'categoryName' => 'required',
            'id' => 'required'
        ]);

        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
        ]);
    }
}
