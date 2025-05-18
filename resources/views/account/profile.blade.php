@extends('layouts.app')
@section('title', __('Profile').' - '.config('app.name'))

@section('content')
    <div class="container mx-auto mt-16 px-6">
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
            <div class="bg-neutral-900 text-white p-4">
                <h1 class="font-heading text-xl inline-flex items-center gap-2"><x-tabler-user-cog /> {{__('Your informations')}}</h1>
            </div>
            <div class="bg-neutral-100 p-4 mb-6">
                <form method="POST">
                    @csrf
                    <x-forms.input type="text" name="name" label="{{__('Fullname')}}" required :value="Auth::user()->name" />
                    <x-forms.input type="email" name="email" label="{{__('Email')}}" required :value="Auth::user()->email" />
                    <x-forms.input type="password" name="password" label="{{__('Password')}}" help="Laissez vide pour conserver votre mot de passe actuel" />
                    <x-forms.input type="password" name="password_confirmation" label="{{__('Confirm password')}}" />

                    <hr class="border border-neutral-200 my-8">
                    <div class="mb-2.5">
                        <h2 class="font-heading text-md mb-1">{{__('Notifications par e-mail')}}</h2>
                        <p class="text-[.75rem] text-gray-500">
                            {{__('Recevez votre sélection de recettes chaque jour par e-mail.')}}
                        </p>
                    </div>
                    <div class="flex items-center pb-7">
                        <input id="email-notifications" name="is_email_notifications_active" type="checkbox" @if(Auth::user()->isEmailNotificationsActive()) checked @endif class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="email-notifications" class="ms-2 text-gray-700">{{__('Activate Email recipe suggestions')}}</label>
                    </div>

                    <div class="flex justify-end">
                        <x-forms.button icon="tabler-device-floppy" label="Save" />
                    </div>
                </form>
            </div>

            <div class="bg-neutral-900 text-white p-4">
                <h1 class="font-heading text-xl inline-flex items-center gap-2"><x-tabler-sun /> {{__('Meteo')}}</h1>
            </div>
            <div x-data="meteo" class="bg-neutral-100 p-4 mb-6">
                <form method="POST" action="{{route('profile.store.meteo')}}">
                    @csrf
                    <div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">{{__("Place's name")}}</label>
                            <div>
                                <input autocomplete="off" type="text" @keydown="updateLocationSearch($el.value)" value="{{Auth::user()->meteo_city}}" name="meteo_city" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-xs focus:outline-hidden focus:ring-indigo-500  sm:text-sm">
                                <span class="ms-1 mt-1 text-[0.75rem] text-gray-500">{{__('Rechercher votre adresse pour remplir automatiquement les coordonnées géographiques.')}}</span>
                                <div class="relative" @click.away="this.show=false">
                                    <div x-cloak x-show="show" class="absolute top-1 left-0 right-0 rounded-md bg-white border p-1">
                                        <template x-for="res in results" class="p-2">
                                            <div class="hover:bg-blue-100 px-2 p-1 rounded-md cursor-pointer" @click="setLocationData(res)" x-text="res.properties?.label"></div>
                                        </template>
                                        <div x-show="results.length === 0" class="p-2">
                                            <p class="text-gray-500">{{__('Searching')}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <x-forms.input type="number" step="any" name="meteo_lat" label="{{__('Latitude')}}" required :value="Auth::user()->meteo_lat" />
                        <x-forms.input type="number" step="any" name="meteo_lon" label="{{__('Longitude')}}" required :value="Auth::user()->meteo_lon" />
                    </div>
                    <div class="flex justify-end">
                        <x-forms.button icon="tabler-device-floppy" label="Save" />
                    </div>
                </form>
            </div>
        </div>



        <div class="w-full max-w-2xl mx-auto">
            <div class="bg-neutral-900 text-white p-4">
                <h1 class="font-heading text-xl inline-flex items-center gap-2"><x-tabler-plug-connected /> {{__('Integrations')}}</h1>
                <div>
                    <p class="text-sm text-gray-200">{{__('Connectez vos comptes pour recevoir votre séléction du jour.')}}</p>
                </div>
            </div>
            <div class="p-4 mb-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    <div class="flex flex-col items-center justify-center" x-data="{ active: {{(int)Auth::user()->isTelegramAccountSetup()}} }">
                        <div class="bg-[#29a9ea] w-20 h-20 p-6 rounded-full flex justify-center items-center mb-2">
                            <x-tabler-brand-telegram class="text-white w-12 h-12"/>
                        </div>
                        <p class="text-nowrap">Telegram : <span :class="active ? 'text-green-500' : 'text-red-500'">{{Auth::user()->isTelegramAccountSetup() ? __("Active") : __('Inactive')}}</span></p>
                        <a target="_blank" href="{{sprintf('https://t.me/%s', str_replace('@', '', config('services.telegram-bot-api.bot_username')))}}" class="mt-2 px-3 py-1 rounded-md bg-[#29a9ea] text-white text-center inline-flex gap-1 items-center" :class="active ? '!hidden': ''"><x-tabler-plug />{{__('Link your account')}}</a>
                    </div>

                    <div class="flex flex-col items-center justify-center" x-data="{ active: {{(int)Auth::user()->isEmailNotificationsActive()}} }">
                        <div class="bg-teal-800 w-20 h-20 p-6 rounded-full flex justify-center items-center mb-2">
                            <x-tabler-mail class="text-white w-12 h-12"/>
                        </div>
                        <p class="text-nowrap">Email : <span :class="active ? 'text-green-500' : 'text-red-500'">{{Auth::user()->isEmailNotificationsActive() ? __("Active") : __('Inactive')}}</span></p>
                    </div>

                    <div class="flex flex-col items-center justify-center" x-data="{ active: {{(int)Auth::user()->isEmailNotificationsActive()}} }">
                        <div class="bg-gray-700 w-20 h-20 p-6 rounded-full flex justify-center items-center mb-2">
                            <x-tabler-question-mark class="text-white w-12 h-12"/>
                        </div>
                        <p class="text-nowrap">Autre... <span class="text-orange-800">Bientôt..</span></p>
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

                document.addEventListener('alpine:init', () => {
                    Alpine.data('meteo', () => ({
                        endpoint: "https://api-adresse.data.gouv.fr/search/",
                        show: false,
                        results: [],
                        updateLocationSearch(val) {
                            if(val.length > 2) {
                                this.show = true;
                                fetch(`${this.endpoint}?q=${val}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        this.show = true;
                                        this.results = data.features;
                                    })
                            }
                        },
                        setLocationData(res) {
                            this.show = false;
                            document.querySelector('input[name="meteo_city"]').value = res.properties.label;
                            document.querySelector('input[name="meteo_lat"]').value = res.geometry.coordinates[1];
                            document.querySelector('input[name="meteo_lon"]').value = res.geometry.coordinates[0];
                        }
                    }))
                })
            </script>
        @endpush
    </div>
@endsection
