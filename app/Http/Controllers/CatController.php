<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class CatController extends Controller
{
    public function add_cat(Request $request)
    {
        $tag = $request->input('tag');
        $slug = $request->input('slug');
        $description = $request->input('description');
        try {
            $this->validate($request,[
                'tag'=> 'required|string|unique:cat,name|min:3|max:70',
                'slug'=> 'required|string|unique:cat,slug|min:3|max:70',
                'description'=> 'required|string|min:3|max:130',
            ]);
        }
        catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);

        }

      $cat= Cat::create([
            'name'=>$tag,
            'slug'=>$slug,
            'description'=>$description
        ]);
        return response()->json([
            'status' => 'success',
            'message'    => 'категория создана',
            'id'=>$cat['id']
        ], 201);
    }
    public function get_cat_list(Request $request)
    {
        $offset =  $request->input('offset');
        $list_tags= Cat::where('id', '>', 0)
            ->offset($offset)
            ->take(20)
            ->get();
        $count = Cat::count();
        return response()->json([
            'status' => 'success',
            'list_tags' => $list_tags,
            'tipes_count' => $count,
        ], 201);
    }
    public function edit_cat(Request $request)
    {
        $tag_name = $request->input('tag');
        $tag_id = $request->input('tag_id');
        $slug = $request->input('slug');
        $description = $request->input('description');

        try {
            $this->validate($request,[
                'tag'=> 'required|string|max:32|min:3|unique:cat,name,'.$tag_id.',id',
                'slug'=> 'required|string|min:3|max:70|unique:cat,slug,'.$tag_id.',id',
                'description'=> 'required|string|min:3|max:130',
            ]);
        }
        catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);

        }
        Cat::where('id', '=', $tag_id)->update([
            'name' =>$tag_name,
            'slug' =>$slug,
            'description' =>$description
        ]);
        return response()->json([
            'status' => 'success',
            'message'    => 'Категория отредактирована',
        ], 201);
    }
    public function get_cat_search(Request $request)
    {
        $queryString=$request->input('req');
        $categories = Cat::where('name', 'LIKE', "%$queryString%")->orderBy('name')->get();
        return $categories;
    }
}
