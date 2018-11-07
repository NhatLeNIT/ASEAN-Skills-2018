<?php

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

Route::get('/', function () {

    \App\User::insert([
        [
            'username' => 'admin',
            'password' => bcrypt('adminpass'),
            'role' => 'ADMIN'
        ],
        [
            'username' => 'user1',
            'password' => bcrypt('user1pass'),
            'role' => 'USER'
        ],
        [
            'username' => 'user2',
            'password' => bcrypt('user2pass'),
            'role' => 'USER'
        ]
    ]);
    return view('welcome');
});
