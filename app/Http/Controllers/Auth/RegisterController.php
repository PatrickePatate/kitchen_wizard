<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\MealTypeEnum;
use App\Models\RecipeDailySelection;
use App\Models\User;
use App\Services\RecipeSelectorService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Request;


class RegisterController extends Controller
{

    public function create()
    {
        if(auth()->check()){
            return redirect()->to(route('home'));
        }

        return view('register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
        ]);

        if($user->id){
            RecipeDailySelection::create([
                'user_id' => $user->id,
                'recipes_selection' => [
                    'starter' => (new RecipeSelectorService)->getRecipe(MealTypeEnum::STARTER)?->id,
                    'main' => (new RecipeSelectorService)->getRecipe(MealTypeEnum::MAIN_COURSE)?->id,
                    'dessert' => (new RecipeSelectorService)->getRecipe(MealTypeEnum::DESSERT)?->id,
                ],
            ]);

            auth()->login($user);
            UserRegistered::dispatch($user);
            return redirect()->to(route('home'));
        }

        return redirect()->back()->withErrors(['name' => __('Something went wrong')]);
    }
}
