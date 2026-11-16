@extends('layouts.app')
@section('content')
    @unless(Auth::user()->hasAtLeastOneNotificationChannelActive())
        <div class="max-w-3xl mx-auto px-6 pt-6">
            <x-alert type="warning">
                <p>{{__("Vous n'avez activé aucun canal de notifications, vous ne recevrez donc pas de suggestions.")}}</p>
                <a href="{{route('profile')}}" class="font-semibold underline">{{__("Activer un canal de notification")}}</a>
            </x-alert>
        </div>
    @endunless
    <livewire:recipe-feed />
@endsection
