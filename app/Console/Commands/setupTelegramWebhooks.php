<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class setupTelegramWebhooks extends Command
{

    protected $signature = 'telegram:setup';


    protected $description = 'Setup telegram webhooks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if(empty(config('services.telegram-bot-api.token'))) {
            $this->error('Please set the TELEGRAM_BOT_API_TOKEN in your .env file first!');
            return;
        }

        $this->info('Setting up telegram webhooks...');

        $res = Http::post('https://api.telegram.org/bot'.config('services.telegram-bot-api.token').'/setWebhook', [
            'url' => route('webhook.telegram'),
        ]);

        $this->info('Setting up commands informations');
        $res = Http::post('https://api.telegram.org/bot'.config('services.telegram-bot-api.token').'/setMyCommands', [
            'commands' => json_encode([
                ['command' => 'start', 'description' => 'Démarrer l\'échange'],
                ['command' => 'link', 'description' => 'Démarrer la procédure de liaison de compte'],
                ['command' => 'code', 'description' => 'Valider la liaison de votre compte'],
                ['command' => 'stop', 'description' => 'Stopper la réception via telegram'],
            ]),
        ]);

        if($res->ok()) {
            $this->info('Telegram webhooks and commands set up successfully!');
        } else {
            $this->error('Failed to set up telegram webhooks!');
            $this->error($res->json()['description'] ?? "Unknown error");
        }
    }
}
