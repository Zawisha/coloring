<?php

namespace App\Http\Controllers;

use App\Models\Colored;
use App\Models\ColoringCat;
use App\Models\ColoringCategory;
use App\Models\Fairy;
use App\Models\FairyCategory;
use App\Models\FairyPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Nette\Schema\ValidationException;

class ImageController extends Controller
{
    public function upload_img(Request $request)
    {
        $user = Auth::user();
        $coloring_name = $request->input('coloring_name');
        $description = $request->input('description');
        $published = $request->input('published');
        if($published=="true")
        {
            $published=1;
        }
        else
        {
            $published=0;
        }

        $selected_category = $request->input('selected_category');
        $selected_cat = $request->input('cat');
        $slug = $request->input('slug');

        try {
            $this->validate($request,[
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'coloring_name'=> 'required|string|min:5|max:40',
                'description'=> 'required|string|min:10|max:300',
                'selected_category'=> 'required',
                'slug'=> 'unique:colored,slug',
            ]);
        }
        catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }
        $image = request()->file();
        $save_to=$user->id.'_'.time().'.jpg';
        Image::make($request->file)->save(public_path('images/colorings/').$save_to);
       $colored_id= Colored::create([
            'name'=>$coloring_name,
            'img' => $save_to,
            'description' => $description,
            'from_user' => $user->id,
           'published'=>$published,
           'slug'=>$slug
        ]);

        $myArray = explode(',', $selected_category);

       foreach ($myArray as $colored)
       {
            ColoringCategory::create([
               'category_id'=>$colored,
               'colored_id' => $colored_id['id'],
           ]);
       }
       if($selected_cat) {
           $myArray_cat = explode(',', $selected_cat);
           foreach ($myArray_cat as $colored) {
               ColoringCat::create([
                   'cat_id' => $colored,
                   'colored_id' => $colored_id['id'],
               ]);
           }
       }

        return response()->json([
            'status' => 'success',
            'message'    => 'Раскраска создана',
            //тут id
            'selected_category'=>$myArray
        ], 201);

    }
    public function upload_fairy(Request $request)
    {
        $user = Auth::user();
        $coloring_name = $request->input('coloring_name');
        $description = $request->input('description');
        $published = $request->input('published');
        if($published=="true")
        {
            $published=1;
        }
        else
        {
            $published=0;
        }
        $selected_category = $request->input('selected_category');
        $slug = $request->input('slug');
        try {
            $this->validate($request,[
                'file' => 'required|image|mimes:jpeg,png,jpg,svg',
                'coloring_name'=> 'required|string|min:5|max:40',
                'description'=> 'required|string|min:10|max:300',
                'selected_category'=> 'required',
                'slug'=> 'unique:fairy,slug',
            ]);
        }
        catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }
        $image = request()->file();
        $save_to=$user->id.'_'.time().'.jpg';
        Image::make($request->file)->save(public_path('images/fairy/').$save_to);
       $colored_id= Fairy::create([
            'name'=>$coloring_name,
            'img_title' => $save_to,
            'description' => $description,
            'from_user' => $user->id,
           'published'=>$published,
           'slug'=>$slug
       ]);

        $myArray = explode(',', $selected_category);

       foreach ($myArray as $colored)
       {
            FairyCategory::create([
               'category_id'=>$colored,
               'fairy_id' => $colored_id['id'],
           ]);
       }
        FairyPage::create([
            'fairy_id'=>$colored_id['id'],
            'description' => 'Описание первой страницы ( стереть при заполнении )',
            'page' => 1,
        ]);

        return response()->json([
            'status' => 'success',
            'message'    => 'Сказка создана',
            'fairy_id'    => $colored_id['id'],
            //тут id
            'selected_category'=>$myArray
        ], 201);

    }
    public function upload_img_edit(Request $request)
    {
        $user = Auth::user();
        $coloring_name = $request->input('coloring_name');
        $color_id = $request->input('color_id');
        $description = $request->input('description');
        $selected_category = $request->input('selected_category');
        $published = $request->input('published');
        $slug = $request->input('slug');
        if($published=="true")
        {
            $published=1;
        }
        else
        {
            $published=0;
        }
        $file = request()->file();;
        try {
            $this->validate($request,[
                'coloring_name'=> 'required|string|min:5|max:25',
                'description'=> 'required|string|min:10|max:40',
                'selected_category'=> 'required',
                'slug'=>  'required',Rule::unique('colored,slug')->where(function($query,$color_id) {
                    $query->where('id', '!=', $color_id);
                })
            ]);
            if($file)
            {
                $this->validate($request,[
                    'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
                ]);
            }
        }
        catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }
        if($file) {
            $to_del = Colored::where('id','=',$color_id)->get();
            $path = public_path()."/images/colorings/".$to_del[0]['img'];
            unlink($path);
            $image = request()->file();
            $save_to = $user->id . '_' . time() . '.jpg';
            Image::make($request->file)->save(public_path('images/colorings/') . $save_to);

            $colored_id = Colored::where('id','=',$color_id)->
            update([
                'name' => $coloring_name,
                'img' => $save_to,
                'description' => $description,
                'published' => $published,
                'slug'=>$slug
            ]);
        }
        else
        {
            $colored_id = Colored::where('id','=',$color_id)->
            update([
                'name' => $coloring_name,
                'description' => $description,
                'published' => $published,
                'slug'=>$slug
            ]);
        }
        ColoringCategory::where('colored_id','=',$color_id)->delete();
        $myArray = explode(',', $selected_category);

        foreach ($myArray as $colored)
        {
            ColoringCategory::create([
                'category_id'=>$colored,
               'colored_id' => $color_id,
           ]);
       }

        return response()->json([
            'status' => 'success',
            'message'    => 'Раскраска создана',
            //тут id
            'selected_category'=>$myArray
        ], 201);

    }

    public function fairy_img_edit(Request $request)
    {
        $user = Auth::user();
        $coloring_name = $request->input('coloring_name');
        $color_id = $request->input('color_id');
        $description = $request->input('description');
        $selected_category = $request->input('selected_category');
        $published = $request->input('published');
        $slug = $request->input('slug');
        if($published=="true")
        {
            $published=1;
        }
        else
        {
            $published=0;
        }
        $file = request()->file();
        try {
            $this->validate($request,[
                'coloring_name'=> 'required|string|min:5|max:25',
                'description'=> 'required|string|min:10|max:300',
                'selected_category'=> 'required',
                'slug'=>  'required',Rule::unique('fairy,slug')->where(function($query,$color_id) {
                    $query->where('id', '!=', $color_id);
                })            ]);
            if($file)
            {
                $this->validate($request,[
                    'file' => 'required|image|mimes:jpeg,png,jpg,svg'
                ]);
            }
        }
        catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }
        if($file) {
            $to_del = Fairy::where('id','=',$color_id)->get();
            $path = public_path()."/images/fairy/".$to_del[0]['img_title'];
            unlink($path);
            $image = request()->file();
            $save_to = $user->id . '_' . time() . '.jpg';
            Image::make($request->file)->save(public_path('images/fairy/') . $save_to);

            $colored_id = Fairy::where('id','=',$color_id)->
            update([
                'name' => $coloring_name,
                'img_title' => $save_to,
                'description' => $description,
                'published' => $published,
                'slug'=>$slug
            ]);
        }
        else
        {
            $colored_id = Fairy::where('id','=',$color_id)->
            update([
                'name' => $coloring_name,
                'description' => $description,
                'published' => $published,
                'slug'=>$slug
            ]);
        }
        FairyCategory::where('fairy_id','=',$color_id)->delete();
        $myArray = explode(',', $selected_category);

        foreach ($myArray as $colored)
        {
            FairyCategory::create([
                'category_id'=>$colored,
                'fairy_id' => $color_id,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message'    => 'Сказка отредактирована',
            //тут id
            'selected_category'=>$myArray
        ], 201);

    }


}
