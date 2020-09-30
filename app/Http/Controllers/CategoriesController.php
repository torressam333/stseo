<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

/**
 * Class Categories Controller
 * @package App\Http\Controllers
 */
class CategoriesController extends Controller
{
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
        $this->deleteFileFromServer($fileName, false);
        return 'Deleted File';
    }

    public function deleteFileFromServer($fileName, $hasFullPath = false)
    {
        if (!$hasFullPath) {
            $filePath = public_path(). '/uploads/'. $fileName;
            file_exists($filePath) ? @unlink($filePath) : null;
        }
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
        //Delete file from server
        $this->deleteFileFromServer($request->iconImage);

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
