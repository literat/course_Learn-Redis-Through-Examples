<?php

namespace App\Http\Controllers;

use App\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class LessonsController extends Controller
{

    public function index()
    {
        $lessons = Lesson::all();

        $inProgressIds = Redis::zrevrange('user.1.inProgress', 0, 2);

        $inProgress = collect($inProgressIds)->map(function ($id) {
            return Lesson::find($id);
        });

        return view('lessons.index', compact('lessons', 'inProgress'));
    }

    public function show(Lesson $lesson)
    {
        Redis::zadd('user.1.inProgress', time(), $lesson->id);
        return view('lessons.show', compact('lesson'));
    }

}
