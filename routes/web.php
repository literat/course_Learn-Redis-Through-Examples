<?php

use Illuminate\Support\Facades\Redis;

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
    $visits = Redis::incr('visits');

    return view('welcome')->withVisits($visits);
});

Route::get('videos/{id}', function ($id) {
    $downloads = Redis::get("videos.{$id}.downloads");

    return view('videos')->withDownloads($downloads);
});

Route::get('videos/{id}/download', function ($id) {
    Redis::incr("videos.{$id}.downloads");

    return back();
});

// function remember($key, $minutes, $callback)
// {
//     if ($value = Redis::get($key)) {
//         return json_decode($value);
//     }

//     Redis::setex($key, 60, $value = $callback());

//     return $value;
// }

interface ArticlesInterface
{
    public function all();
}

class CachableArticles implements ArticlesInterface
{
    private $articles;

    public function __construct($articles)
    {
        $this->articles = $articles;
    }

    public function all()
    {
        return \Cache::remember('articles.all', 60 * 60, function () {
            return $this->articles->all();
        });
    }
}

class Articles implements ArticlesInterface
{
    public function all()
    {
        return App\Article::all();
    }
}

App::bind('ArticlesInterface', function () {
    return new CachableArticles(new Articles);
});

Route::get('articles', function (ArticlesInterface $articles) {
    return $articles->all();
});

Route::get('articles/trending', function () {
    $trending = Redis::zrevrange('trending_articles', 0, 2);

    $trending = App\Article::hydrate(
        array_map('json_decode', $trending)
    );

    return $trending;
});

Route::get('articles/{article}', function (App\Article $article) {
    Redis::zincrby('trending_articles', 1, $article);

    return $article;
});

Route::get('users/{id}/stats', function ($id) {
    // $user2stats = [
    //     'favorites' => $user->favourites()->count(),
    //     'watchLaters' => $user->watchLaters()->count(),
    //     'completions' => $user->completions()->count(),
    // ];

    // Redis::hmset('users.2.stats', $user2stats);

    return Redis::hgetall("users.{$id}.stats");
});

Route::get('favorite-video', function () {
    // Auth::id();
    Redis::hincrby('users.1.stats', 'favorites', 1);

    return redirect('/users/1/stats');
});

Route::get('cache', function () {
    Cache::put('foo', ['name' => 'laracasts', 'age' => 3], 10);

    return Cache::get('foo');
});

Route::resource('lessons', 'LessonsController');
