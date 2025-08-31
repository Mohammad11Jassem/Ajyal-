<?php

namespace App\Jobs;

use App\Helpers\PushNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $message;
    public $fcm_token;

    /**
     * Create a new job instance.
     */
    public function __construct(array $message, string $fcm_token)
    {
        $this->message = $message;
        $this->fcm_token = $fcm_token;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        PushNotification::sendNotification($this->message, $this->fcm_token);
    }
}
