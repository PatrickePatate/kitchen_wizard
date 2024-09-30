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
                <h1 class="font-heading">{{__('Login')}}</h1>
            </div>
            <div class="bg-neutral-100 p-6">
                <form method="POST">
                    @csrf
                    <x-forms.input type="email" name="email" label="{{__('Email')}}" required />

                    <x-forms.input type="password" name="password" label="{{__('Password')}}" required />

                    <div class="flex justify-end">
                        <button type="submit" class="px-5 py-3 bg-blue-700 font-medium text-white rounded-md">
                            {{__('Login')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
