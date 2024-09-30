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

        if($res->ok()) {
            $this->info('Telegram webhooks set up successfully!');
        } else {
            $this->error('Failed to set up telegram webhooks!');
            $this->error($res->json()['description'] ?? "Unknown error");
        }
    }
}
