@extends('layouts.app', ['hasHeader' => false])
@section('title', __('Register').' - '.config('app.name'))
@section('meta')
    <meta property="og:image" content="{{asset('images/website_cover.jpg')}}" />
@endsection
@section('content')
    <div class="w-full h-[100dvh] overflow-x-hidden ">
        <div class="grid grid-cols-1 md:grid-cols-5 h-full gap-5 p-6">
            <div class="w-full h-full my-auto mx-auto md:col-span-2">
                <div class="mx-auto h-full flex flex-col justify-between max-w-md">
                    <h1 class="font-heading py-3">
                        <x-icon-logo class="w-48"></x-icon-logo>
                    </h1>

                    <div>
                        <h1 class="font-heading md:text-2xl mb-4">{{__('Register')}}</h1>
                        <div class="my-5 p-2 flex items-start gap-2 rounded-md text-white shadow-md bg-blue-500">
                            <div class="w-10 h-10 mt-1">
                                <x-tabler-info-circle-filled></x-tabler-info-circle-filled>
                            </div>
                            <p>
                                {{__("Trouve de l'inspiration pour tes repas du quotidien, met de côté des recettes qui te plaisent et recoit tous les jours une entrée, un plat et un dessert pour ta journée !")}}
                            </p>
                        </div>
                        <form method="POST">
                            @csrf
                            <x-forms.input type="text" name="name" label="{{__('Fullname')}}" required />

                            <x-forms.input type="email" name="email" label="{{__('Email')}}" required />

                            <x-forms.input type="password" name="password" label="{{__('Password')}}" required />

                            <x-forms.input type="password" name="password_confirmation" label="{{__('Password confirmation')}}" required />

                            <div class="flex justify-end items-center gap-2">
                                <p class="text-sm py-2 text-gray-700 flex-grow "><a href="{{route('login')}}">{{__('Already have an account ? Login.')}}</a></p>
                                <button type="submit" class="px-5 py-3 bg-blue-700 font-medium text-white text-nowrap rounded-md">
                                    {{__('Register')}}
                                </button>
                            </div>
                        </form>


                    </div>

                    <div class="h-2">

                    </div>
                </div>
            </div>

            <div class="hidden md:block relative col-span-3 ">
                <img src="{{asset('images/register_background.jpeg')}}" class="object-center object-cover w-full h-full max-h-[95dvh]  rounded-lg"/>
                <a class="absolute bottom-2 right-3 text-white text-sm font-light" href="https://www.freepik.com/free-photo/fresh-organic-mixed-fruit-vegetables_13311286.htm#page=2&query=vegetable&position=16&from_view=keyword&track=ais_hybrid&uuid=391df862-4275-4180-902d-fe7b0d217b7d">Image by rawpixel.com on freepik</a>
            </div>
        </div>
    </div>
@endsection
