<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarText;
use App\Models\TelegramInvite;
use App\Models\TelegramPhones;
use App\Models\UserForInvite;
use App\Models\VkSearchGroup;
use Illuminate\Http\Request;
use App\Traits\AuthMaddellineTrait;

class CarController extends Controller
{
    use AuthMaddellineTrait;

    public function invite_users(Request $request)
    {
        sleep(3);


// обновить версию maddelineproto, попробовать поставить через composer
        $phone = $request->input('phone');
        $group = $request->input('group');

//        $technology_id = $request->input('technology_id');
        $technology_id=TelegramInvite::where('group',$group)->get();
        $technology_id=$technology_id[0]['technology'];

        $MadelineProto = $this->madAuth($phone);


        //проверяем на количество пользователей
//                    try {
//                        $channels_ChannelParticipants = $MadelineProto->channels->getParticipants(['channel' => $group, 'filter' => ['_' => 'channelParticipantsSearch', 'q' => 'a'], 'offset' => 0, 'limit' => 1, 'hash' => 0,]);
//                    }
//                    catch (\Exception $e) {
//                        return response()->json([
//                            'status' => 'success',
//                            'success'   => 'no',
//                            'message'   => 'ошибка авторизации',
//                            'critical'   => 'yes',
//                            'error'  => $e
//                        ], 200);
//                    }
//        return dd('qwe');

        $dev_id = UserForInvite::where('technology_id', '=', $technology_id)->where('invited', '=', '0')->where('username', '!=', 'NO')->first();
        if ($dev_id === null) {
            return response()->json([
                'status' => 'success',
                'success'   =>'no',
                'message'   => $technology_id . ' ' . 'пустая',
                'critical'   => 'yes',
            ], 200);
        }
        if ($dev_id['username'] != 'NO') {
            //если висит и не добавляет раскоменти строчку ниже, там наверное пользователь которого уже добавили или пригласили
//                          return dd($dev_id);
//                        return dd($group[$i].' '.$phones_start[$i][$s].' '.[$dev_id['username']].' '. $technology_id[$i]);

            try {
//                            $channels_ChannelParticipants = $MadelineProto->channels->inviteToChannel(['channel' => $group, 'users' => [$dev_id['username']]]);
                $channels_ChannelParticipants = $MadelineProto->channels->inviteToChannel(['channel' => $group, 'users' => [$dev_id['username']]]);
            }
            catch (\Exception $e) {

//                            UserForInvite::where('user_id', '=', $dev_id['user_id'])->update([
//                                'invited' => '1'
//                            ]);

//                            if($e->getMessage()=="PEER_FLOOD")
//                            {
//                                return response()->json([
//                                    'status' => 'success',
//                                    'success'   =>'no',
//                                    'message'   =>$e->getMessage(),
//                                    'critical'   => 'yes',
//                                ], 200);
//                            }
//                            else
//                            {

                UserForInvite::where('user_id', '=', $dev_id['user_id'])->update([
                    'invited' => '1'
                ]);

                return response()->json([
                    'status' => 'success',
                    'success'   =>'no',
                    'message'   => $e,
                    'critical'   => 'no',
                ], 200);
//                            }

            }

            UserForInvite::where('user_id', '=', $dev_id['user_id'])->update([
                'invited' => '1'
            ]);

            return response()->json([
                'status' => 'success',
                'message'   => 'ok',
                'success'   => 'yes',
                'critical'   => 'no',
            ], 200);

        } else
        {
            UserForInvite::where('user_id', '=', $dev_id['user_id'])->update([
                'invited' => '1'
            ]);
            return response()->json([
                'status' => 'success',
                'success'   =>'no',
                'message'   => 'no username',
                'critical'   => 'no',
            ], 200);
        }
    }

    public function get_start_data_telegram()
    {
        $data=TelegramInvite::all();
        foreach ($data as $one_group)
        {
            $count_of_users=UserForInvite::where('technology_id',$one_group['technology'] )
                ->where('invited',0 )
                ->where('username','!=','NO' )
                -> count();
            $one_group['count_user']=$count_of_users;
        }

        return response()->json([
            'status' => 'success',
            'message'   => $data,
            'success'   => 'yes',
        ], 200);
    }

    public function send_code(Request $request)
    {
        $auth_code = $request->input('auth_code');
        $phone = $request->input('phone');
        if($phone=='')
        {
            return 'пустой телефон';
        }
        if($auth_code=='')
        {
            return 'пустой код';
        }
        $phones = TelegramPhones::where('phone','=',$phone) ->get();
        $settings = [
            'app_info' => [ // Эти данные мы получили после регистрации приложения на https://my.telegram.org
                'api_id' => $phones[0]['api_id'],
                'api_hash' => $phones[0]['api_hash'],
                'device_model'=>'Desktop',
            ],
            'logger' => [ // Вывод сообщений и ошибок
                'logger' => 3, // выводим сообещения через echo
                'logger_level' => 4, // выводим только критические ошибки.
            ],
            'serialization' => [
                'serialization_interval' => 300,
                //Очищать файл сессии от некритичных данных.
                //Значительно снижает потребление памяти при интенсивном использовании, но может вызывать проблемы
                'cleanup_before_serialization' => true,
            ],
        ];
//
        $MadelineProto = new \danog\MadelineProto\API(public_path().'/my_mad_sessions/'.$phone.'/session.madeline', $settings);
        //костыль
        $MadelineProto->phone_login($phones[0]['phone']);
        $MadelineProto->complete_phone_login($auth_code);
        return 'ok';
    }
    public function autorization(Request $request)
    {
//        if (!file_exists('madeline.php')) {
//            copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
//        }
        $phone = $request->input('phone');
        if($phone=='')
        {
            return 'Пустой телефон';
        }
        $phones = TelegramPhones::where('phone', '=',$phone) ->get();

        if(!(is_dir(public_path().'/my_mad_sessions/'.$phone)))
        {
            mkdir(public_path().'/my_mad_sessions/'.$phone);
        }
        if (file_exists(public_path().'/my_mad_sessions/'.$phone.'/session.madeline')) {
            unlink(public_path().'/my_mad_sessions/'.$phone.'/session.madeline');
        }
        if (file_exists(public_path().'/my_mad_sessions/'.$phone.'/session.madeline.callback.ipc')) {
            unlink(public_path().'/my_mad_sessions/'.$phone.'/session.madeline.callback.ipc');
        }
        if (file_exists(public_path().'/my_mad_sessions/'.$phone.'/session.madeline.ipcState.php')) {
            unlink(public_path().'/my_mad_sessions/'.$phone.'/session.madeline.ipcState.php');
        }
        if (file_exists(public_path().'/my_mad_sessions/'.$phone.'/session.madeline.ipcState.php.lock')) {
            unlink(public_path().'/my_mad_sessions/'.$phone.'/session.madeline.ipcState.php.lock');
        }
        if (file_exists(public_path().'/my_mad_sessions/'.$phone.'/session.madeline.lightState.php')) {
            unlink(public_path().'/my_mad_sessions/'.$phone.'/session.madeline.lightState.php');
        }
        if (file_exists(public_path().'/my_mad_sessions/'.$phone.'/session.madeline.lightState.php.lock')) {
            unlink(public_path().'/my_mad_sessions/'.$phone.'/session.madeline.lightState.php.lock');
        }
        if (file_exists(public_path().'/my_mad_sessions/'.$phone.'/session.madeline.lock')) {
            unlink(public_path().'/my_mad_sessions/'.$phone.'/session.madeline.lock');
        }
        if (file_exists(public_path().'/my_mad_sessions/'.$phone.'/session.madeline.safe.php')) {
            unlink(public_path().'/my_mad_sessions/'.$phone.'/session.madeline.safe.php');
        }
        if (file_exists(public_path().'/my_mad_sessions/'.$phone.'/session.madeline.safe.php.lock')) {
            unlink(public_path().'/my_mad_sessions/'.$phone.'/session.madeline.safe.php.lock');
        }

        $settings = [
            'app_info' => [ // Эти данные мы получили после регистрации приложения на https://my.telegram.org
                'api_id' => $phones[0]['api_id'],
                'api_hash' => $phones[0]['api_hash'],
                'device_model'=>'Desktop',
            ],
            'logger' => [ // Вывод сообщений и ошибок
                'logger' => 3, // выводим сообещения через echo
                'logger_level' => 4, // выводим только критические ошибки.
            ],
            'serialization' => [
                'serialization_interval' => 300,
                //Очищать файл сессии от некритичных данных.
                //Значительно снижает потребление памяти при интенсивном использовании, но может вызывать проблемы
                'cleanup_before_serialization' => true,
            ],
        ];
        $MadelineProto = new \danog\MadelineProto\API(public_path().'/my_mad_sessions/'.$phone.'/session.madeline', $settings);
        //$MadelineProto = new \danog\MadelineProto\API('session.madeline', $settings);
        $MadelineProto->phone_login($phones[0]['phone']);
        return 'ok';
    }
    public function get_telegram_users()
    {
        return view('telegram.get_telegram_users')->with('auth_user',  auth()->user());
    }
    public function telegram_sending_component()
    {
        return view('telegram.telegram_sending_component')->with('auth_user',  auth()->user());
    }
    public function get_phones()
    {
        $phone_list = TelegramPhones::all();
        return $phone_list;
    }
    public function helper()
    {
        return view('main.car');
    }
    public function check_user(Request $request)
    {
        $user_id =  $request->input('user_id');
        $picked =  $request->input('picked');
        $user_id=trim($user_id);
        $group_post_last=Car::where('user_id',$user_id)->get();
        if (!($group_post_last->isEmpty())) {
            return response()->json([
                'status' => 'success',
                'isset' => 'уже был',
            ], 200);
        }
        else
        {
            Car::create([
                'user_id'=>$user_id,
            ]);
            $city_text=CarText::where('city_id',$picked)->get();
            $text=$city_text[0]['text'];
            return response()->json([
                'status' => 'success',
                'isset' => 'не было, можно писать',
                'text'=>$text
            ], 200);
        }
    }

    public function get_vk_posts()
    {
        //нужно добавить конкретную технологию для старта в отдельную таблицу, чтобы каждый скрипт начинал со своей группы
        $final_result=[];
        $groups=VkSearchGroup::all();
        foreach ($groups as $group)
        {
            sleep(1);
            try {
                $xml = json_decode(file_get_contents("https://api.vk.com/method/wall.get?owner_id=-".$group['group_id']."&count=20&v=5.131&access_token=ebb4e240a677264ecf6de2ecfc9ab45a83a90c3773f309e92677ab19c624d132e9abe70e44d09a41cba59"));
            }
            catch (\Throwable $e)
            {
                return response()->json([
                    'status' => 'ошибка в получении списка постов группы',
                    'error'    =>  $e,
                    'group'    =>  $group_id,
                ], 200);
            }
            //  $xml->response->items=array_reverse($xml->response->items);


            $posts=[];
            foreach ($xml->response->items as $post)
            {
                // return $post->attachments[0]->type;
                $add_fl=1;
                //убираем пост со ссылкой или на статью
                if(isset($post->attachments))
                {
                    //убираем закреплённый пост
                    //убираем пост с посмотреть объявление
                    if((isset($post->is_pinned))||($post->attachments[0]->type=='link')||(isset($post->copy_history)))
                    {
                        $add_fl=0;
                    }
                }
                else
                {
                    $add_fl=0;
                }
                if($add_fl==1)
                {
                    array_unshift($posts, $post);
                }

            }

            $group_post_last=VkSearchGroup::where('group_id',$group['group_id'])->get('last_post');
            foreach ($posts as $post)
            {
                //закреплённый пост не берём и смотрим чтобы пост был новее чем тот что я уже брал
                if($post->id>$group_post_last[0]->last_post)
                {
                    array_push($final_result,$post);

//                VKPosts::where('group_id',$group_id)->
//                update([
//                    'last_post' => $post->id,
//                ]);
                }
            }
        }
        dd($final_result);
        return response()->json([
            'status' => 'собрано',
            'posts_list' =>$final_result,
        ], 200);
    }

}
