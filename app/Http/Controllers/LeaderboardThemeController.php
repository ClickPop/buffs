<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaderboardTheme;
use Illuminate\Http\Request;
use Validator;

class LeaderboardThemeController extends Controller
{
    public function store(StoreLeaderboardTheme $request)
    {
        $validated = $request->validated();


    }
}
