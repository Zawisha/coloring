<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class TagController extends Controller
{
    public function add_tag(Request $request)
    {
        $tag = $request->input('tag');
        $slug = $request->input('slug');
        try {
            $this->validate($request,[
                'tag'=> 'required|string|unique:categories,name|min:3|max:32',
                'slug'=> 'required|string|unique:categories,slug|min:3|max:70',

            ]);
        }
        catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);

        }
        Categories::create([
            'name'=>$tag,
            'slug'=>$slug,
        ]);
        return response()->json([
            'status' => 'success',
            'message'    => 'Тег создан',
        ], 201);
    }
    public function get_tag_list(Request $request)
    {
        $offset =  $request->input('offset');
        $list_tags= Categories::where('id', '>', 0)
            ->orderBy('name', 'ASC')
            ->offset($offset)
            ->limit(50)
            ->get();
        $count = Categories::count();
        return response()->json([
            'status' => 'success',
            'list_tags' => $list_tags,
            'tipes_count' => $count,
        ], 201);
    }
    public function edit_tag(Request $request)
    {
        $tag_name = $request->input('tag');
        $tag_id = $request->input('tag_id');
        $slug = $request->input('slug');

        try {
            $this->validate($request,[
                'tag'=> 'required|string|min:3|max:32|unique:categories,name,'.$tag_id.',id',
                'slug'=> 'required|string|min:3|max:70|unique:categories,slug,'.$tag_id.',id',
            ]);
        }
        catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);

        }
        Categories::where('id', '=', $tag_id)->update([
            'name' =>$tag_name,
            'slug' =>$slug
        ]);
        return response()->json([
            'status' => 'success',
            'message'    => 'Тег отредактирован',
        ], 201);
    }
}
