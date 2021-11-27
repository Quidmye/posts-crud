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

Route::get('/', function () {
    return view('welcome');
});
Route::group([
        'prefix' => 'api',
        'namespace' => 'App\Http\Controllers'
    ], function () {
        Route::group([
            'prefix' => 'posts',
            'namespace' => 'Posts'
        ], function () {
            Route::get('/', 'PostsController@index');
            Route::post('/', 'PostsController@create')
                ->withoutMiddleware([
                    \App\Http\Middleware\VerifyCsrfToken::class
                ]);
            Route::patch('/{id}', 'PostsController@update')
                ->withoutMiddleware([
                    \App\Http\Middleware\VerifyCsrfToken::class
                ]);
            Route::delete('/{id}', 'PostsController@delete')
                ->withoutMiddleware([
                    \App\Http\Middleware\VerifyCsrfToken::class
                ]);
            Route::get('/{id}', 'PostsController@view')->where('id', '[0-9]+');

            Route::get('/{post_id}/comments/{id}', 'CommentsController@postComments')
                ->where([
                    'id' => '[0-9]+',
                    'post_id' => '[0-9]+'
                ]);
            Route::delete('/{post_id}/comments/{id}', 'CommentsController@delete')
                ->where([
                    'id' => '[0-9]+',
                    'post_id' => '[0-9]+'
                ])
                ->withoutMiddleware([
                    \App\Http\Middleware\VerifyCsrfToken::class
                ]);

            Route::post('/{post_id}/comments', 'CommentsController@create')
                ->withoutMiddleware([
                    \App\Http\Middleware\VerifyCsrfToken::class
                ]);

            Route::patch('/{post_id}/comments/{id}', 'CommentsController@update')
                ->withoutMiddleware([
                    \App\Http\Middleware\VerifyCsrfToken::class
                ])
                ->where([
                    'id' => '[0-9]+',
                    'post_id' => '[0-9]+'
                ]);
        });
        Route::group([
            'prefix' => 'comments',
            'namespace' => 'Posts'
        ], function () {
            Route::get('/{id}', 'CommentsController@view')->where('id', '[0-9]+');
            Route::delete('{id}', 'CommentsController@delete')->where('id', '[0-9]+');
            Route::patch('/{id}', 'CommentsController@updateComment')
                ->withoutMiddleware([
                    \App\Http\Middleware\VerifyCsrfToken::class
                ])
                ->where([
                    'id' => '[0-9]+'
                ]);
        });
});
