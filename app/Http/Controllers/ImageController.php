<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\Categories;
use App\Models\Colored;
use App\Models\ColoringCat;
use App\Models\ColoringCategory;
use App\Models\ColoringUserOption;
use App\Models\Fairy;
use App\Models\FairyCat;
use App\Models\FairyCategory;
use App\Models\FairyPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Nette\Schema\ValidationException;

class ImageController extends Controller
{
    public function get_carusel_images(Request $request)
    {
        $coloring_id = $request->input('id');
        $colored_id_all = ColoringUserOption::where('id', '=', $coloring_id)->get();
        $same_colorings = ColoringUserOption::
        where('coloring_id', '=', $colored_id_all[0]['coloring_id'])
            -> where('id', '!=', $colored_id_all[0]['id'])
            ->get();
        return response()->json([
            'status' => 'success',

            //тут id
            'coloring_imgs'=>$same_colorings
        ], 201);

    }
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
                'file' => 'required|image|mimes:jpeg,png,jpg',
                'coloring_name'=> 'required|string|min:5|max:70',
                'description'=> 'required|string|min:5|max:300',
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
        $save_small=$user->id.'_'.time().'small'.'.jpg';
        Image::make($request->file)->resize(300,null,function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('images/colorings/').$save_small);
        Image::make($request->file)->save(public_path('images/colorings/').$save_to);
        $colored_id= Colored::create([
            'name'=>$coloring_name,
            'img' => $save_to,
            'img_small' => $save_small,
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

    public function upload_img_user_option(Request $request)
    {

        $user = Auth::user();
        $user_name = $request->input('user_name');
        $slug = $request->input('slug');
        $age = $request->input('age');
        try {
            $this->validate($request,[
                'file' => 'required|image|mimes:jpeg,png,jpg',
                'user_name'=> 'required|string|min:2|max:40',
                'age'=> 'required|string|min:1|max:20',
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
        $id=ColoringUserOption::orderBy('id','desc')->get();
        if($id->isEmpty())
        {
            $id_plus=1;
        }
        else
        {
            $id_plus=$id[0]['id']+1;
        }

        $save_to=$slug.'_'.$id_plus.'.jpg';
        Image::make($request->file)->save(public_path('images/colorings/').$save_to);
        $colored_id = Colored::where('slug', '=', $slug)
            ->get();

        $save_small=$slug.'_'.$id_plus.'small'.'.jpg';
        Image::make($request->file)->resize(300,null,function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('images/colorings/').$save_small);
        ColoringUserOption::create([
            'coloring_id'=>$colored_id[0]['id'],
            'user_id' => $user->id,
            'img' => $save_to,
            'img_small' => $save_small,
            'user_name' => $user_name,
            'age'=>$age,
            'slug'=>$slug.'_'.$id_plus,
        ]);

        return response()->json([
            'status' => 'success',
            'message'    => 'Раскраска создана',
        ], 201);

    }

    public function upload_img_user(Request $request)
    {

        $user = Auth::user();
        $coloring_name = $request->input('coloring_name');
        $description = $request->input('description');
        $published=0;
        $selected_category = $request->input('selected_category');
        $slug = $request->input('slug');
        $extension = $request->input('extension');

        try {
            $this->validate($request,[
                'file' => 'required|image|mimes:jpeg,png,jpg|max:8192',
                'coloring_name'=> 'required|string|min:5|max:70',
                'description'=> 'required|string|min:5|max:300',
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
        $save_to=$user->id.'_'.time().'.'.$extension;
        Image::make($request->file)->save(public_path('images/colorings/').$save_to);

        $save_small=$user->id.'_'.time().'small'.'.'.$extension;
        Image::make($request->file)->resize(300,null,function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('images/colorings/').$save_small);
        $colored_id= Colored::create([
            'name'=>$coloring_name,
            'img' => $save_to,
            'img_small' => $save_small,
            'description' => $description,
            'from_user' => $user->id,
            'published'=>$published,
            'slug'=>$slug
        ]);

        $myArray = explode(',', $selected_category);
        foreach ($myArray as $one_tag_name)
        {
                $categories_list = Categories::where('name', '=', $one_tag_name)
                    ->get();

                if ($categories_list->isEmpty()) {
                    $categories_new= Categories::create([
                        'name'=>$one_tag_name
                    ]);


                    ColoringCategory::create([
                        'category_id'=>$categories_new['id'],
                        'colored_id' => $colored_id['id'],
                    ]);

                }
                else
                {

                    ColoringCategory::create([
                        'category_id'=>$categories_list[0]['id'],
                        'colored_id' => $colored_id['id'],
                    ]);
                }

        }

        return response()->json([
            'status' => 'success',
            'message'    => 'Раскраска создана'
            //тут id

        ], 201);

    }
    public function get_cat_img(Request $request)
    {
        $cat_id =  $request->input('cat_id');
        $cat_list = Cat::where('id', '=', $cat_id)
            ->get('img');
        return response()->json([
            'status' => 'success',
            'cat_list' => $cat_list,
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
        $selected_cat = $request->input('cat');
        if($selected_cat) {
            $myArray_cat = explode(',', $selected_cat);
            foreach ($myArray_cat as $colored) {
                FairyCat::create([
                    'cat_id' => $colored,
                    'colored_id' => $colored_id['id'],
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message'    => 'Сказка создана',
            'fairy_id'    => $colored_id['id'],
            //тут id
            'selected_category'=>$myArray
        ], 201);

    }

    public function upload_img_cat(Request $request)
    {
        $user = Auth::user();
        $file = request()->file();
        $cat_id = $request->input('cat_id');

            try {
                $this->validate($request, [
                    'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
                ]);
            }
            catch (ValidationException $exception) {
                return response()->json([
                    'status' => 'error',
                    'message'    => 'Error',
                    'errors' => $exception->errors(),
                ], 422);
            }
//            $to_del = Colored::where('id','=',$color_id)->get();
//            $path = public_path()."/images/colorings/".$to_del[0]['img'];
//            unlink($path);
            $image = request()->file();
            $save_to = $user->id . '_' . time() . '.jpg';
            Image::make($request->file)->save(public_path('images/cat/') . $save_to);
            Cat::where('id','=',$cat_id)->
            update([
                'img' => $save_to,

            ]);

        return response()->json([
            'status' => 'success',
            'message'    => 'file added',
        ], 201);

    }

    public function upload_img_cat_edit(Request $request)
    {
        $user = Auth::user();
        $file = request()->file();
        $cat_id = $request->input('cat_id');

        try {
            $this->validate($request, [
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
            ]);
        }
        catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }
            $to_del = Cat::where('id','=',$cat_id)->get();
        if($to_del[0]['img'] != null)
        {
            try {
                $path = public_path() . "/images/cat/" . $to_del[0]['img'];
                unlink($path);
            }
            catch (\Throwable $e)
            {

            }
        }

        $image = request()->file();
        $save_to = $user->id . '_' . time() . '.jpg';
        Image::make($request->file)->save(public_path('images/cat/') . $save_to);
        Cat::where('id','=',$cat_id)->
        update([
            'img' => $save_to
        ]);
        return response()->json([
            'status' => 'success',
            'message'    => 'file added',
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
        $selected_cat = $request->input('cat');
        if($published=="true"||$published==1)
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
                'coloring_name'=> 'required|string|min:5|max:70',
                'description'=> 'required|string|min:10|max:300',
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
            $path = public_path()."/images/colorings/".$to_del[0]['img_small'];
            unlink($path);
            $image = request()->file();
            $save_to = $user->id . '_' . time() . '.jpg';

            $save_small=$user->id.'_'.time().'small'.'.jpg';
            Image::make($request->file)->resize(300,null,function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('images/colorings/').$save_small);
            Image::make($request->file)->save(public_path('images/colorings/') . $save_to);

            $colored_id = Colored::where('id','=',$color_id)->
            update([
                'name' => $coloring_name,
                'img' => $save_to,
                'img_small' => $save_small,
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
        ColoringCat::where('colored_id','=',$color_id)->delete();

        if($selected_cat) {
            $myArray_cat = explode(',', $selected_cat);
            foreach ($myArray_cat as $colored) {
                ColoringCat::create([
                    'cat_id' => $colored,
                    'colored_id' => $color_id,
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
