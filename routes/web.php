<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
Auth::routes();
//разделы фронта
Route::get('/', [App\Http\Controllers\MainViewController::class, 'index'])->name('home');
//Route::get('/coloring',  [App\Http\Controllers\ColoringController::class, 'index'])->name('coloring');
Route::post('/get_categories',  [App\Http\Controllers\ColoringController::class, 'get_categories'])->name('get_categories');
Route::get('/cat',  [App\Http\Controllers\MainViewController::class, 'front_cat_list'])->name('front_cat_list');
Route::get('/cat/{slug}',  [App\Http\Controllers\MainViewController::class, 'front_cat_one'])->name('front_cat_one');
Route::post('/front_get_tag_list',  [App\Http\Controllers\MainViewController::class, 'front_get_tag_list'])->name('front_get_tag_list');
Route::post('/get_coloring_list', [\App\Http\Controllers\ColoringController::class, 'get_coloring_list'])->name('get_coloring_list');
Route::post('/get_coloring_list_by_cat', [\App\Http\Controllers\ColoringController::class, 'get_coloring_list_by_cat'])->name('get_coloring_list_by_cat');
Route::post('/get_coloring_list_liked', [\App\Http\Controllers\ColoringController::class, 'get_coloring_list_liked'])->name('get_coloring_list_liked');
Route::get('/coloring/{slug}', [\App\Http\Controllers\MainViewController::class, 'get_one_coloring'])->name('get_one_coloring');
Route::get('/fairy-list', [\App\Http\Controllers\MainViewController::class, 'front_fairy_list'])->name('front_fairy_list');
Route::post('/get_fairy_list', [\App\Http\Controllers\FairyController::class, 'get_fairy_list'])->name('get_fairy_list');
Route::get('/fairy/{slug}', [\App\Http\Controllers\MainViewController::class, 'get_one_fairy'])->name('get_one_fairy');
Route::get('/fairy-read/{fairy}', [\App\Http\Controllers\MainViewController::class, 'fairy_read'])->name('fairy_read');
Route::post('/get_fairy_page_data', [\App\Http\Controllers\FairyController::class, 'get_fairy_page_data'])->name('get_fairy_page_data');
Route::get('/video-list', [\App\Http\Controllers\MainViewController::class, 'front_video_list'])->name('front_video_list');
Route::get('/video/{slug}', [\App\Http\Controllers\MainViewController::class, 'get_one_video'])->name('get_one_video');
Route::post('/get_video_list', [App\Http\Controllers\VideoController::class, 'get_video_list'])->name('get_video_list');
Route::get('/download/{file}', [App\Http\Controllers\MainViewController::class, 'download'])->name('download');
Route::get('/print', [App\Http\Controllers\MainViewController::class, 'print'])->name('print');
Route::post('/get_cat_search',  [App\Http\Controllers\CatController::class, 'get_cat_search'])->name('get_cat_search');
Route::post('/get_fairy_id_by_slug',  [App\Http\Controllers\FairyController::class, 'get_fairy_id_by_slug'])->name('get_fairy_id_by_slug');
Route::post('/getOneFrontColoring', [App\Http\Controllers\ColoringController::class, 'getOneFrontColoring'])->name('getOneFrontColoring');
Route::post('/setLike', [App\Http\Controllers\LikeController::class, 'setLike'])->name('setLike');
Route::post('/get_cat_list', [\App\Http\Controllers\CatController::class, 'get_cat_list'])->name('get_cat_list');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\MainViewController::class, 'profile'])->name('profile');
    Route::get('/new_password', [App\Http\Controllers\MainViewController::class, 'new_password'])->name('new_password');
    Route::post('/change_profile_data', [App\Http\Controllers\ProfileController::class, 'change_profile_data'])->name('change_profile_data');
    Route::post('/changePasswordPost', [App\Http\Controllers\ProfileController::class, 'changePasswordPost'])->name('changePasswordPost');
    Route::post('/logout', [App\Http\Controllers\ProfileController::class, 'logout'])->name('logout');
    Route::get('/liked', [App\Http\Controllers\MainViewController::class, 'liked'])->name('liked');
    Route::post('/upload_crop', [App\Http\Controllers\ProfileController::class, 'upload_crop'])->name('upload_crop');
    Route::post('/get_user_params', [App\Http\Controllers\ProfileController::class, 'get_user_params'])->name('get_user_params');
    Route::post('/get_user_params_main', [App\Http\Controllers\ProfileController::class, 'get_user_params_main'])->name('get_user_params_main');

});

//админка
Route::middleware(['editor'])->group(function () {
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');
Route::get('/admin/add_coloring', [App\Http\Controllers\AdminController::class, 'add_coloring'])->name('admin_add_coloring');
Route::get('/admin/add_fairy_title', [App\Http\Controllers\AdminController::class, 'add_fairy_title'])->name('add_fairy_title');
Route::get('/admin/add_fairy/{fairy_id}/{fairy_page}', [App\Http\Controllers\AdminController::class, 'add_fairy'])->name('add_fairy');
Route::get('/admin/edit_coloring/{post_id}', [App\Http\Controllers\AdminController::class, 'edit_coloring'])->name('edit_coloring');
Route::get('/admin/edit_fairy/{fairy_id}', [App\Http\Controllers\AdminController::class, 'edit_fairy'])->name('edit_fairy');
Route::post('/get_edit_color', [App\Http\Controllers\ColoringController::class, 'get_edit_color'])->name('get_edit_color');
Route::post('/get_edit_fairy', [App\Http\Controllers\FairyController::class, 'get_edit_fairy'])->name('get_edit_fairy');
Route::get('/admin/add_coloring_success', [App\Http\Controllers\AdminController::class, 'add_coloring_success'])->name('add_coloring_success');
Route::get('/admin/edit_coloring_success/{id}', [App\Http\Controllers\AdminController::class, 'edit_coloring_success'])->name('edit_coloring_success');
Route::get('/admin/tags', [App\Http\Controllers\AdminController::class, 'tags'])->name('tags');
Route::get('/admin/cat', [App\Http\Controllers\AdminController::class, 'cat'])->name('cat');
Route::get('/admin/coloring_list', [App\Http\Controllers\ColoringController::class, 'coloring_list'])->name('coloring_list');
Route::get('/admin/fairy_list', [App\Http\Controllers\FairyController::class, 'fairy_list'])->name('fairy_list');
Route::post('/upload_img', [\App\Http\Controllers\ImageController::class, 'upload_img'])->name('upload_img');
Route::post('/upload_fairy', [\App\Http\Controllers\ImageController::class, 'upload_fairy'])->name('upload_fairy');
Route::post('/upload_img_edit', [\App\Http\Controllers\ImageController::class, 'upload_img_edit'])->name('upload_img_edit');
Route::post('/upload_img_cat', [\App\Http\Controllers\ImageController::class, 'upload_img_cat'])->name('upload_img_cat');
Route::post('/upload_img_cat_edit', [\App\Http\Controllers\ImageController::class, 'upload_img_cat_edit'])->name('upload_img_cat_edit');
Route::post('/fairy_img_edit', [\App\Http\Controllers\ImageController::class, 'fairy_img_edit'])->name('fairy_img_edit');
Route::post('/get_cat_img', [\App\Http\Controllers\ImageController::class, 'get_cat_img'])->name('get_cat_img');
Route::post('/moderation_color', [\App\Http\Controllers\ColoringController::class, 'moderation_color'])->name('moderation_color');
Route::post('/moderation_fairy', [\App\Http\Controllers\FairyController::class, 'moderation_fairy'])->name('moderation_fairy');
Route::post('/moderation_video', [\App\Http\Controllers\VideoController::class, 'moderation_video'])->name('moderation_video');
Route::post('/get_fairy_pagination', [\App\Http\Controllers\FairyController::class, 'get_fairy_pagination'])->name('get_fairy_pagination');
Route::post('/add_tag', [\App\Http\Controllers\TagController::class, 'add_tag'])->name('add_tag');
Route::post('/edit_tag', [\App\Http\Controllers\TagController::class, 'edit_tag'])->name('edit_tag');
Route::post('/get_tag_list', [\App\Http\Controllers\TagController::class, 'get_tag_list'])->name('get_tag_list');
Route::post('/store_from_editor', [\App\Http\Controllers\FairyController::class, 'store_from_editor'])->name('store_from_editor');
Route::post('/upload_ckeditor', [\App\Http\Controllers\FairyController::class, 'upload_ckeditor'])->name('upload_ckeditor');
Route::post('/delete_fairy', [\App\Http\Controllers\FairyController::class, 'delete_fairy'])->name('delete_fairy');
Route::post('/delete_colored', [\App\Http\Controllers\ColoringController::class, 'delete_colored'])->name('delete_colored');
Route::post('/delete_video', [\App\Http\Controllers\VideoController::class, 'delete_video'])->name('delete_video');
Route::get('/admin/add_video', [App\Http\Controllers\AdminController::class, 'add_video'])->name('add_video');
Route::post('/upload_video', [\App\Http\Controllers\VideoController::class, 'upload_video'])->name('upload_video');
Route::get('/admin/edit_video/{post_id}', [App\Http\Controllers\AdminController::class, 'edit_video'])->name('edit_video');
Route::post('/get_edit_video', [App\Http\Controllers\VideoController::class, 'get_edit_video'])->name('get_edit_video');
Route::post('/save_edit_video', [App\Http\Controllers\VideoController::class, 'save_edit_video'])->name('save_edit_video');
Route::get('/video_list', [App\Http\Controllers\AdminController::class, 'video_list'])->name('video_list');
Route::post('/change_permission', [App\Http\Controllers\AdminController::class, 'change_permission'])->name('change_permission');
Route::get('/users_list', [App\Http\Controllers\AdminController::class, 'users_list'])->name('users_list')->middleware('admin');
Route::post('/get_users_list', [App\Http\Controllers\AdminController::class, 'get_users_list'])->name('get_users_list')->middleware('admin');
Route::post('/add_cat', [\App\Http\Controllers\CatController::class, 'add_cat'])->name('add_cat');
Route::post('/edit_cat', [\App\Http\Controllers\CatController::class, 'edit_cat'])->name('edit_cat');

});
