<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Colored;
use App\Models\ColoringCategory;
use Illuminate\Http\Request;

class ColoringController extends Controller
{
//    public function index()
//    {
//        return view('sections.coloring');
//    }
    public function get_categories(Request $request)
    {
        //unidev comment
        $queryString=$request->input('req');
        $categories = Categories::where('name', 'LIKE', "%$queryString%")->orderBy('name')->get();
        return $categories;
    }
    public function coloring_list()
    {
        return view('admin.coloring_list');
    }
    public function moderation_color(Request $request)
    {
        $id=  $request->input('id');
        $published =  $request->input('published');
        Colored::where('id','=',$id)->
        update([
            'published' => $published,
        ]);
    }
    public function get_coloring_list(Request $request)
    {
        $offset =  $request->input('offset');
        $search_id =  $request->input('search_id');
        $front =  $request->input('front');
        //если идет поиск по тегу
        if($search_id)
{
    $list_colored = ColoringCategory::where('category_id', '=', $search_id)
        ->offset($offset)
        ->whereHas('colored', function ($query) {
            $query->where('published', '=', 1);
        })
        ->limit(12)
        ->get();
    $col_res=[];
    foreach ($list_colored as $col)
    {
        $col_res[]=$col['colored'];
    }
    $col_res=collect($col_res);
    $count = ColoringCategory::where('category_id', '=', $search_id)
        ->whereHas('colored', function ($query) {
            $query->where('published', '=', 1);
        })
        ->count();
    return response()->json([
        'status' => 'success',
        'list_colored' => $col_res,
        'tipes_count' => $count,
    ], 201);
}
        //счетчик
//общий случай без тегов

        $list_colored = Colored::where('id', '>', '0')
            ->when($front, function($q){
                return $q->where('published','=', '1');
            })
            ->offset($offset)
            ->limit(12)
            ->get();
        $count = Colored::where('id', '>', '0')
            ->when($front, function($q){
                return $q->where('published','=', '1');
            })
            ->count();
        return response()->json([
            'status' => 'success',
            'list_colored' => $list_colored,
            'tipes_count' => $count,
        ], 201);
    }
    public function get_edit_color(Request $request)
    {
        $color_id =  $request->input('color_id');
        $list_colored = Colored::where('id', '=', $color_id)
            ->with('categories')
            ->get();
        if($list_colored)
        {
            return $list_colored;
        }
        return response()->json([
            'status' => 'error',
            'message'    => 'Error',
        ], 422);
    }
    public function delete_colored(Request $request)
    {
        $fairy_id =  $request->input('id');
        ColoringCategory::where('colored_id', '=', $fairy_id) ->delete();
        $to_del = Colored::where('id','=',$fairy_id)->get();
        $path = public_path()."/images/colorings/".$to_del[0]['img'];
        unlink($path);
        Colored::where('id','=',$fairy_id)->delete();
        return response()->json([
            'status' => 'success',
        ], 201);
    }
}
