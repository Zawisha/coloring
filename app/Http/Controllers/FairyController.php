<?php

namespace App\Http\Controllers;

use App\Models\Colored;
use App\Models\Fairy;
use App\Models\FairyCategory;
use App\Models\FairyImg;
use App\Models\FairyPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\Schema\ValidationException;
use Throwable;

class FairyController extends Controller
{
    //сохранение данных из ckeditor
    //если будет пользовательская загрузка то переделать одновременное сохранение изображений, например с сохранением в сессии
    public function store_from_editor(Request $request)
    {
        $post_data= $request->input('description');
        $fairy_page=$request->input('current_page');
        $current_fairy=$request->input('current_fairy');
        try {
            $this->validate($request,[
                'description'=> 'required|string|min:1|max:16000',
                'current_fairy'=> 'required|integer',
                'current_page'=> 'required|integer',
            ]);
        }

        catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }
        $fairy_in_db_isset=FairyPage::where('fairy_id','=',$request->input('current_fairy'))->where('page','=',$request->input('current_page'))->get();
       //если тоже самое что и было пришло
        if (!($fairy_in_db_isset->isEmpty())) {
            if ($fairy_in_db_isset[0]['description'] == $post_data) {
                return redirect()->route('add_fairy', ['fairy_id' => $request->input('current_fairy'), 'fairy_page' => $fairy_page]);
            }
        }

        preg_match_all('#\d+_+\d+\.[a-z]+#', $post_data,  $matches);
        //удаляем старые картинки и загружаем новые ТУТ
        FairyImg::where('fairy_id','=',$request->input('current_fairy'))->get();
        //удаляем те что были но их удалили в новой странице
        //получаем весь массив того что есть на конкретной странице в конкретной сказке
        $fairy_old_img=FairyImg::where('fairy_id','=',$request->input('current_fairy'))->where('page_id','=',$request->input('current_page'))->get();
       $old_list=[];
        foreach ($fairy_old_img as $old)
        {
            $flag=0;
            foreach ($matches[0] as $img_path)
            {
               if($old['refer']==$img_path)
               {
                   $flag=1;
               }
            }
            if($flag==0)
            {
                $old_list[]=$old['refer'];
            }

        }
        //удаляем не нужные изображения
        foreach ($old_list as $old_one) {
            $fairy_old_img = FairyImg::where('refer', '=',$old_one )->delete();
            $path = public_path()."/images/fairy/".$old_one;
            try
            {
                unlink($path);
            }
            catch(Throwable $e)
            {
            }
        }
        //убираем из него то что пришло и реально существует
        //удаляем то чего нету
        //сохраняем в БД новые изображения которые реально существуют
        foreach ($matches[0] as $img_path)
        {
            FairyImg::where('refer','=',$img_path)->
            update([
                'checked' => '1',
                'fairy_id' => $current_fairy,
                'page_id' => $fairy_page,
            ]);
        }
        //удаляем те изображения которых нет
        $to_del = FairyImg::where('checked','=','0')->get();
        foreach ($to_del as $del)
        {
            $path = public_path()."/images/fairy/".$del['refer'];
            try
            {
                unlink($path);
            }
            catch(Throwable $e)
            {
            }
        }
        FairyImg::where('checked','=','0')->delete();

        $fairy_in_db_isset=FairyPage::where('fairy_id','=',$request->input('current_fairy'))->where('page','=',$request->input('current_page'))->get();
        if ($fairy_in_db_isset->isEmpty()) {
            //если новая страничка
        FairyPage::create([
            'fairy_id'=>$request->input('current_fairy'),
            'description' => $post_data,
            'page' => $request->input('current_page'),
        ]);
        return redirect()->route('add_fairy',['fairy_id'=>$request->input('current_fairy'),'fairy_page'=>$fairy_page] );
        }
        else
        {
            //если не новая, то редактируем
            FairyPage::where('fairy_id','=',$request->input('current_fairy'))->where('page','=',$request->input('current_page'))->
            update([
                'description' => $post_data,
            ]);
            return redirect()->route('add_fairy',['fairy_id'=>$request->input('current_fairy'),'fairy_page'=>$fairy_page] );
        }
    }
    //сохранение изображения временное
    public function upload_ckeditor(Request $request)
    {
        $user = Auth::user();
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {
            $fileName = $user->id.'_'.time().'.'.$extension;
            $request->file('upload')->move(public_path('images/fairy'), $fileName);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('images/fairy/'.$fileName);
            FairyImg::create([
                'refer'=>$fileName,
                //ВОТ ТУТ ДОБАВИТЬ FAIRY ID
                'fairy_id' => '0',
                'checked' => '0',
            ]);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
        }
        $response ='The file type does not match the specified ones. Close this window';
        header('Content-Type: text/html; charset=utf-8');
        echo json_encode($response);
    }

    public function get_fairy_pagination(Request $request)
    {
        $current_fairy_id =  $request->input('current_fairy_id');
        $count = FairyPage::where('fairy_id', '=', $current_fairy_id)
            ->count();
        return response()->json([
            'status' => 'success',
            'tipes_count' => $count,
        ], 201);
    }
    public function fairy_list()
    {
        return view('admin.fairy_list');
    }

    public function get_fairy_page_data(Request $request)
    {
        $current_fairy =  $request->input('current_fairy');
        $current_page =  $request->input('current_page');
        try {
            $this->validate($request,[
                'current_fairy'=> 'required|integer',
                'current_page'=> 'required|integer',
            ]);
        }
        catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }

        $fairy_in_db_isset=FairyPage::where('fairy_id','=',$current_fairy)->where('page','=',$current_page)->get();
        $count=FairyPage::where('fairy_id','=',$current_fairy)->count();
        //если страница есть в бд
        if (!($fairy_in_db_isset->isEmpty())) {
            return response()->json([
                'status' => 'success',
                'fairy_data' => $fairy_in_db_isset,
                'tipes_count' => $count,
            ], 201);
        }
        else
        {
            //404
        }


    }

    public function get_fairy_list(Request $request)
    {

        $offset =  $request->input('offset');
        $search_id =  $request->input('search_id');
        $front =  $request->input('front');
        //если идет поиск по тегу
        if($search_id)
        {
            $list_colored = FairyCategory::where('category_id', '=', $search_id)
                ->offset($offset)
                ->whereHas('fairy', function ($query) {
                    $query->where('published', '=', 1);
                })
                ->limit(12)
                ->get();
            $col_res=[];
            foreach ($list_colored as $col)
            {
                $col_res[]=$col['fairy'];
            }
            $col_res=collect($col_res);
            $count = FairyCategory::where('category_id', '=', $search_id)
                ->whereHas('fairy', function ($query) {
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

        $list_colored = Fairy::where('id', '>', '0')
            ->when($front, function($q){
                return $q->where('published','=', '1');
            })
            ->offset($offset)
            ->limit(12)
            ->get();
        $count = Fairy::where('id', '>', '0')
            ->when($front, function($q){
                return $q->where('published','=', '1');
            })
            ->count();
        return response()->json([
            'status' => 'success',
            'list_colored' => $list_colored,
            'tipes_count' => $count,
        ], 201);


        //  $published =  $request->input('published');
       // $offset =  $request->input('offset');

       // $list_colored = Fairy::where('id', '>', '0')
       //     ->offset($offset)
        //    ->limit(12)
        //    ->get();
       // $count = Fairy::where('id', '>', '0')
        //    ->count();
       // return response()->json([
       //     'status' => 'success',
        //    'list_colored' => $list_colored,
        //    'tipes_count' => $count,
       // ], 201);
    }
    public function moderation_fairy(Request $request)
    {
    $id=  $request->input('id');
    $published =  $request->input('published');
    Fairy::where('id','=',$id)->
    update([
        'published' => $published,
    ]);
    }
    public function get_edit_fairy(Request $request)
    {
        $color_id =  $request->input('color_id');
        $list_colored = Fairy::where('id', '=', $color_id)
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
    public function delete_fairy(Request $request)
    {
        $fairy_id =  $request->input('id');
        FairyCategory::where('fairy_id', '=', $fairy_id) ->delete();
        $to_del = FairyImg::where('fairy_id','=',$fairy_id)->get();
        foreach ($to_del as $del)
        {
            $path = public_path()."/images/fairy/".$del['refer'];
            try
            {
                unlink($path);
            }
            catch(Throwable $e)
            {
            }
        }
        $to_del = Fairy::where('id','=',$fairy_id)->get();
        $path = public_path()."/images/fairy/".$to_del[0]['img_title'];
        unlink($path);
        Fairy::where('id','=',$fairy_id)->delete();
        FairyImg::where('fairy_id', '=', $fairy_id) ->delete();
        FairyPage::where('fairy_id', '=', $fairy_id) ->delete();
        return response()->json([
            'status' => 'success',
        ], 201);
    }
}
