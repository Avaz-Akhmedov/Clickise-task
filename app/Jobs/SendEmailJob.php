<?php

namespace App\Jobs;

use App\Notifications\ConfirmUpdateNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct(protected  $receiver, protected string $message)
    {
        //
    }


    public function handle(): void
    {
        $this->receiver->notify(new ConfirmUpdateNotification($this->message));
    }
}
