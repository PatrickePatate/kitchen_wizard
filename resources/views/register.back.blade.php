@extends('layouts.app', ['hasHeader' => false])
@section('title', __('Login').' - '.config('app.name'))

@section('content')
    <div class="w-full h-[100dvh] overflow-x-hidden flex justify-center items-center">
        <div class="w-full max-w-md">

            <h1 class="font-heading py-3">
                <x-tabler-chef-hat class="inline me-2"></x-tabler-chef-hat>
                {{config('app.name')}}
            </h1>

            <div class="flex justify-between bg-neutral-200 p-6">
                <h1 class="font-heading">{{__('Register')}}</h1>
            </div>
            <div class="bg-neutral-100 p-6">
                <form method="POST">
                    @csrf
                    <x-forms.input type="text" name="name" label="{{__('Fullname')}}" required />

                    <x-forms.input type="email" name="email" label="{{__('Email')}}" required />

                    <x-forms.input type="password" name="password" label="{{__('Password')}}" required />

                    <x-forms.input type="password" name="password_confirmation" label="{{__('Password confirmation')}}" required />

                    <div class="flex justify-end">
                        <button type="submit" class="px-5 py-3 bg-blue-700 font-medium text-white rounded-md">
                            {{__('Register')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
