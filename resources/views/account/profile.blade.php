@extends('layouts.app')
@section('title', __('Login').' - '.config('app.name'))

@section('content')
    <div class="container mx-auto mt-16">
        <div class="w-full max-w-2xl mx-auto">
            @if(session()->has('success'))
                <div class="p-5 bg-green-300 bg-opacity-85 mb-6">
                    {{session('success')}}
                </div>
            @endif
            <div class="flex items-stretch gap-6 mb-6">
                <img src="{{Auth::user()->avatar}}" alt="avatar" class="w-32 h-32">
                <div class="flex flex-col justify-between">
                    <div class="">
                        <h1 class="text-xl font-heading mb-2">{{Auth::user()->name}}</h1>
                        <div class="text-gray-700 h-fit">
                            <p>{{__('Account creation:')}} {{Auth::user()->created_at?->translatedFormat('d F Y')}} </p>
                        </div>
                    </div>
                    <div>
                        <a href="https://gravatar.com/profile" target="_blank" class="text-blue-500">{{__('Set your avatar on')}} Gravatar</a>
                    </div>
                </div>
            </div>
            <div class="bg-neutral-200 p-4">
                <h1 class="font-heading text-xl">{{__('Your informations')}}</h1>
            </div>
            <div class="bg-neutral-100 p-4 mb-6">
                <form method="POST">
                    @csrf
                    <x-forms.input type="text" name="name" label="{{__('Name')}}" required value="{{Auth::user()->name}}" />
                    <x-forms.input type="email" name="email" label="{{__('Email')}}" required value="{{Auth::user()->email}}" />
                    <x-forms.input type="password" name="password" label="{{__('Password')}}" help="Laissez vide pour conserver votre mot de passe actuel" />
                    <x-forms.input type="password" name="password_confirmation" label="{{__('Confirm password')}}" />

                    <hr class="border border-neutral-200">

                    <div class="flex items-center py-5">
                        <input id="email-notifications" name="is_email_notifications_active" type="checkbox" @if(Auth::user()->isEmailNotificationsActive()) checked @endif class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="email-notifications" class="ms-2 text-sm font-medium text-gray-700">{{__('Activate Email recipe suggestions')}}</label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-5 py-3 bg-blue-700 font-medium text-white rounded-md">
                            {{__('Save')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>



        <div class="w-full max-w-2xl mx-auto">
            <div class="bg-neutral-200 p-4">
                <h1 class="font-heading text-xl">{{__('Integrations')}}</h1>
            </div>
            <div class="p-4 mb-6">
                <div class="grid grid-cols-3 sm:grid-cols-4">
                    <div class="flex flex-col items-center justify-center" x-data="{active: {{Auth::user()->isTelegramAccountSetup()}}}">
                        <div class="bg-[#29a9ea] w-20 h-20 p-6 rounded-full flex justify-center items-center mb-2">
                            <x-tabler-brand-telegram class="text-white w-12 h-12"/>
                        </div>
                        <p class="text-nowrap">Telegram : <span :class="active ? 'text-green-500' : 'text-red-500'">{{Auth::user()->isTelegramAccountSetup() ? __("Active") : __('Inactive')}}</span></p>
                    </div>

                    <div class="flex flex-col items-center justify-center" x-data="{active: {{Auth::user()->isTelegramAccountSetup()}}}">
                        <div class="bg-teal-800 w-20 h-20 p-6 rounded-full flex justify-center items-center mb-2">
                            <x-tabler-mail class="text-white w-12 h-12"/>
                        </div>
                        <p class="text-nowrap">Email : <span :class="active ? 'text-green-500' : 'text-red-500'">{{Auth::user()->isTelegramAccountSetup() ? __("Active") : __('Inactive')}}</span></p>
                    </div>
                </div>

            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    let pass = document.querySelector('#input-password');
                    let passConf = document.querySelector('#input-password_confirmation');
                    document.addEventListener('input', () => {
                        if(pass.value.length > 0 && pass.value !== passConf.value) {
                            passConf.classList.add('border-red-500');
                            pass.classList.add('border-red-500');
                        } else {
                            passConf.classList.remove('border-red-500');
                            pass.classList.remove('border-red-500');

                            if(pass.value.length > 0 && pass.value === passConf.value) {
                                passConf.classList.add('border-green-500');
                                pass.classList.add('border-green-500');
                            } else {
                                passConf.classList.remove('border-green-500');
                                pass.classList.remove('border-green-500');
                            }
                        }
                    })
                })
            </script>
        @endpush
    </div>
@endsection
