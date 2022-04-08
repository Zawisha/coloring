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
        try {
            $this->validate($request,[
                'tag'=> 'required|string|unique:categories,name|min:3|max:32',
            ]);
        }
        catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);

        }
        Cat::create([
            'name'=>$tag
        ]);
        return response()->json([
            'status' => 'success',
            'message'    => 'категория создана',
        ], 201);
    }
    public function get_cat_list(Request $request)
    {
        $offset =  $request->input('offset');
        $list_tags= Cat::where('id', '>', 0)->
            offset($offset)
            ->limit(50)
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
        try {
            $this->validate($request,[
                'tag'=> 'required|string|unique:categories,name|min:3|max:32',
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
            'name' =>$tag_name
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
