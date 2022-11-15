<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function() {
    $crawler = Goutte::request('GET', 'https://dantri.com.vn/the-thao/hau-truong.htm');
    $crawler->filter('h3.article-title')->each(function ($node) {
    //   dump($node->text() ."<br>");
    echo $node->text() ."<br>";
    });
    return view('welcome');
});