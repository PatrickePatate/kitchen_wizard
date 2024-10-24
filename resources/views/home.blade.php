@use(Carbon\Carbon)
@extends('layouts.app')
@section('content')
    @if(Auth::user()->hasAtLeastOneNotificationChannelActive())
        <div class="container mx-auto min-h-[100dvh]">
            <!-- Feed header -->
            <div class="max-w-3xl mx-auto border-x px-6 pt-6 pb-4 ">
                <div class="rounded-xl bg-purple-950 text-white p-4 mb-4 flex items-center gap-4">
                    <div>
                        <x-tabler-alert-triangle></x-tabler-alert-triangle>
                    </div>
                    <div>
                        <p>{{__("Vous n'avez activé aucun canal de notifications, vous ne recevrez donc pas de suggestions.")}}</p>
                        <a href="{{route('profile')}}" class="text-white font-semibold">{{__("Activer un canal de notification")}}</a>
                    </div>
            </div>
        </div>
    @endif
    <livewire:recipe-feed />
@endsection
