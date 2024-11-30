@extends('layouts.app', ['hasHeader' => false])
@section('title', __('Login').' - '.config('app.name'))

@section('content')
    <div class="w-full h-[100dvh] overflow-x-hidden ">
        <div class="grid grid-cols-1 md:grid-cols-5 h-full gap-5 p-6">
            <div class="w-full h-full my-auto mx-auto md:col-span-2">
                <div class="mx-auto h-full flex flex-col justify-between max-w-md">
                    <h1 class="font-heading py-3">
                        <x-icon-logo class="w-48"></x-icon-logo>
                    </h1>

                    <div>
                        <h1 class="font-heading md:text-2xl mb-4">{{__('Login')}}</h1>

                        <form method="POST">
                            @csrf
                            <x-forms.input type="email" name="email" label="{{__('Email')}}" required />

                            <x-forms.input type="password" name="password" label="{{__('Password')}}" required />

                            <x-forms.check name="remember" label="{{__('Remember me')}}" />
                            <div class="flex justify-end items-center gap-2">
                                <p class="text-sm py-2 text-gray-700 flex-grow "><a href="{{route('register')}}">{{__('No account ? Register.')}}</a></p>
                                <button type="submit" class="px-5 py-3 bg-blue-700 font-medium text-white text-nowrap rounded-md">
                                    {{__('Login')}}
                                </button>
                            </div>
                        </form>


                    </div>

                    <div class="h-2">

                    </div>
                </div>
            </div>

            <div class="hidden md:block relative col-span-3">
                <img src="{{asset('images/login_background.jpeg')}}" class="object-center object-cover h-full rounded-lg">
                <a class="absolute bottom-2 right-3 text-white text-sm font-light" href="https://www.freepik.com/free-photo/raw-fresh-organic-vegetables-desk_3492645.htm#query=vegetable&position=8&from_view=keyword&track=ais_hybrid&uuid=7ac276a7-e02a-4c6b-8586-5947d9905c42">Image by freepik</a>
            </div>
        </div>
    </div>
@endsection
