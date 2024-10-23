<?php

namespace App\Http\Controllers;

use App\MealTypeEnum;
use App\Models\Recipe;
use App\Services\RecipeSelectorService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }
}
