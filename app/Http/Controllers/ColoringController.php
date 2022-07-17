<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\Categories;
use App\Models\Colored;
use App\Models\ColoringCat;
use App\Models\ColoringCategory;
use App\Models\Like;
use App\Models\LikeCounter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

   public function get_coloring_list_by_cat(Request $request)
    {
        $search_slug =  $request->input('search_slug');
        $offset =  $request->input('offset');
        //получаем id категории
        $cat_id = Cat::where('slug', $search_slug)
            ->first('id');
        $cat_list = ColoringCat::where('cat_id', $cat_id['id'])
            ->offset($offset)
            ->limit(20)
            ->whereHas('colored', function ($query) {
                $query->where('published', '=', 1);
            })
            ->with('colored')
            ->get();
        $count = ColoringCat::where('cat_id', $cat_id['id'])
            ->whereHas('colored', function ($query) {
                $query->where('published', '=', 1);
            })
            ->count();
       $col_ids_arr=[];
       foreach ($cat_list as $one_colored)
       {
           array_push($col_ids_arr, $one_colored['id']);
       }
       $list_like=[];
       $userId = Auth::id();
       if(Auth::id())
       {
           $list_like = Like::whereIn('post_id', $col_ids_arr)->where('user_id', $userId)->where('type_of_content', '1')->get();
       }
       $list_like_counter = LikeCounter::whereIn('post_id', $col_ids_arr)->where('type_of_content', '1')->get();
       foreach ($cat_list as $key=>$colored)
       {
           $colored['type_of_like']='0';
           $colored['count_of_like']='0';
           foreach ($list_like as $like)
           {
               if($colored['id']==$like['post_id'])
               {
                   $cat_list[$key]['type_of_like']=$like['type_of_like'];
               }
           }
           foreach ($list_like_counter as $like_count)
           {
               if($colored['id']==$like_count['post_id'])
               {
                   $cat_list[$key]['count_of_like']=$like_count['count_of_like'];
               }
           }
       }
       return response()->json([
           'status' => 'success',
           'list_colored' => $cat_list,
           'like_arr' => $list_like,
           'tipes_count' => $count
       ], 201);
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
        ->limit(20)
        ->whereHas('colored', function ($query) {
            $query->where('published', '=', 1);
        })
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
            ->limit(20)
            ->get();
        $count = Colored::where('id', '>', '0')
            ->when($front, function($q){
                return $q->where('published','=', '1');
            })
            ->count();

//        return dd($list_colored);
        $col_ids_arr=[];
        foreach ($list_colored as $one_colored)
        {
            array_push($col_ids_arr, $one_colored['id']);
        }
        $list_like=[];
        $userId = Auth::id();
        if(Auth::id())
        {
        $list_like = Like::whereIn('post_id', $col_ids_arr)->where('user_id', $userId)->where('type_of_content', '1')->get();
        }
        $list_like_counter = LikeCounter::whereIn('post_id', $col_ids_arr)->where('type_of_content', '1')->get();
        foreach ($list_colored as $key=>$colored)
        {
            $colored['type_of_like']='0';
            $colored['count_of_like']='0';
            foreach ($list_like as $like)
            {
                if($colored['id']==$like['post_id'])
                {
                    $list_colored[$key]['type_of_like']=$like['type_of_like'];
                }
            }
            foreach ($list_like_counter as $like_count)
            {
                if($colored['id']==$like_count['post_id'])
                {
                    $list_colored[$key]['count_of_like']=$like_count['count_of_like'];
                }
            }
        }
        return response()->json([
            'status' => 'success',
            'list_colored' => $list_colored,
            'tipes_count' => $count,
            'like_arr' => $list_like,
        ], 201);
    }
    public function get_coloring_list_liked(Request $request)
    {
        $offset =  $request->input('offset');
        $search_id =  $request->input('search_id');
        $front =  $request->input('front');
        $user = Auth::user();
//        $user->id;
        $like_user = like::where('user_id',  $user->id)
          ->where('type_of_like','1')
          ->where('type_of_content','1')
            ->offset($offset)
            ->limit(20)
          ->get('post_id');
        $count = like::where('user_id',  $user->id)
            ->where('type_of_like','1')
            ->where('type_of_content','1')
            ->count();
        $list_colored = Colored::whereIn('id', $like_user)
            ->get();
//        $count = Colored::whereIn('id', $like_user)
//            ->count();
        $col_ids_arr=[];
        foreach ($list_colored as $one_colored)
        {
            array_push($col_ids_arr, $one_colored['id']);
        }
        $list_like=[];
        $userId = Auth::id();
        if(Auth::id())
        {
            $list_like = Like::whereIn('post_id', $col_ids_arr)->where('user_id', $userId)->where('type_of_content', '1')->get();
        }
        $list_like_counter = LikeCounter::whereIn('post_id', $col_ids_arr)->where('type_of_content', '1')->get();
        foreach ($list_colored as $key=>$colored)
        {
            $colored['type_of_like']='0';
            $colored['count_of_like']='0';
            foreach ($list_like as $like)
            {
                if($colored['id']==$like['post_id'])
                {
                    $list_colored[$key]['type_of_like']=$like['type_of_like'];
                }
            }
            foreach ($list_like_counter as $like_count)
            {
                if($colored['id']==$like_count['post_id'])
                {
                    $list_colored[$key]['count_of_like']=$like_count['count_of_like'];
                }
            }
        }
        return response()->json([
            'status' => 'success',
            'list_colored' => $list_colored,
            'tipes_count' => $count,
            'like_arr' => $list_like,
        ], 201);
    }
    public function getOneFrontColoring(Request $request)
    {
        $slug=$request->colorSlug;
        $coloring=Colored::where('slug','=',$slug)->get();
//                return dd($coloring[0]['id']);
        //общее количество лайков
        $like_counter = LikeCounter::where('post_id', $coloring[0]['id'])->where('type_of_content', '1')->get();
//        return dd($like_counter[0]['count_of_like']);
        if($like_counter->isEmpty())
        {
            $coloring[0]['count_of_like']='0';
        }
        else
        {
            $coloring[0]['count_of_like']=$like_counter[0]['count_of_like'];
        }
//        return dd(Auth::id());
        $userId = Auth::id();
        if($userId!==null)
        {
            //вытаскиваем состояние его лайка
            $like_status = Like::where('post_id', '=',$coloring[0]['id'])->where('user_id','=', $userId)->where('type_of_content','=', '1')->get();
            if($like_status->isEmpty())
            {
                $coloring[0]['type_of_like']='0';
            }
            //если лайк уже был поставлен этим пользователем
            else
            {
                $coloring[0]['type_of_like']=$like_status[0]['type_of_like'];

            }
        }
        else
        {
            $coloring[0]['type_of_like']='0';
        }
        $user_name = User::where('id', '=',$coloring[0]['from_user'])->get('nickname');
        $coloring[0]['nickname']=$user_name[0]['nickname'];
        $cat = ColoringCat::where('colored_id', '=',$coloring[0]['id'])->limit(6)->with('cat')->get();
        $category = ColoringCategory::where('colored_id', '=',$coloring[0]['id'])->limit(6)->with('categories')->get();
        return response()->json([
            'status' => 'success',
            'coloring' => $coloring,
            'cat' => $cat,
            'category' => $category
        ], 200);
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
