<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use App\Models\Fairy;
use App\Models\FairyPage;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.main');
    }
    public function add_video()
    {
        return view('admin.add_video');
    }
    public function add_coloring()
    {
        return view('admin.add_coloring');
    }
    public function add_fairy(Request $request)
    {
        //если нет то редиректим на создание
      //  $currentURL = url()->current();
        //получаем номер сказки
        $fairy_id=$request->fairy_id;
        $fairy_in_db=Fairy::where('id','=',$fairy_id)->get();
        if ($fairy_in_db->isEmpty()) {
            return view('admin.add_fairy');
        }
        //проверяем не первая ли это страница
        //получаем номер страницы та что пришла
        $fairy_page=$request->fairy_page;
        //получаем последнюю страницу в БД
        $fairy_in_db_isset=FairyPage::where('fairy_id','=',$fairy_id)->orderBy('page', 'DESC')->get();
        if ($fairy_in_db_isset->isEmpty()) {
            return view('admin.add_fairy');
        }
        $fairy_in_db=FairyPage::where('fairy_id','=',$fairy_id)->orderBy('page', 'DESC')->first();
        //тут новая страница а значит пустая
        if(($fairy_page-$fairy_in_db['page'])>1)
        {
            $fairy_page=$fairy_in_db['page']+1;
            return redirect()->route('add_fairy',['fairy_id'=>$fairy_id,'fairy_page'=>$fairy_page] ) ;
        }

        //проверяем последняя ли это страница и редиректим только на неё
        $page_data=FairyPage::where('fairy_id','=',$fairy_id)->where('page','=',$fairy_page)->get();
        if ($page_data->isEmpty()) {
            return view('admin.fairy');
        }
        return view('admin.fairy', ['description_from_db' => $page_data['0']['description']]);
    }
    public function add_fairy_title()
    {
        return view('admin.add_fairy');
    }
    public function add_coloring_success()
    {
        return view('admin.add_coloring_success');
    }
    public function edit_coloring()
    {
        return view('admin.edit_coloring');
    }
    public function edit_fairy()
    {
        return view('admin.edit_fairy');
    }
    public function edit_video()
    {
        return view('admin.edit_video');
    }
    public function tags()
    {
        return view('admin.tags');
    }
    public function edit_coloring_success()
    {
        return view('admin.edit_coloring_success');
    }
    public function video_list()
    {
        return view('admin.video_list');
    }
    public function users_list()
    {
        return view('admin.users_list');
    }
    public function get_users_list(Request $request)
    {
        $offset =  $request->input('offset');

        $list_users = User::where('id', '>', '0')
            ->offset($offset)
            ->limit(12)
            ->get();
        foreach ($list_users as $key=>$one_user)
        {
            $id=Admins::where('user_id', '=', $one_user['id'])
                ->get();
            if ($id->isEmpty()) {
                $one_user->perm='0';
            }
            else{
                $one_user->perm=$id[0]['user_permission_id'];
                }
        }

        $count = User::where('id', '>', '0')
            ->count();
        return response()->json([
            'status' => 'success',
            'list_users' => $list_users,
            'tipes_count' => $count,
        ], 201);
    }
    public function change_permission(Request $request)
    {
        $user_id =  $request->input('user_id');
        $user_perm =  $request->input('user_perm');
        $id=Admins::where('user_id', '=', $user_id)
            ->get();
        if ($id->isEmpty()) {
            Admins::create([
                'user_id'=>$user_id,
                'user_permission_id' => $user_perm,
            ]);
        }
        else{
            Admins::where('user_id', '=', $user_id)->
            update([
                'user_permission_id' => $user_perm,
            ]);        }
    }
}
