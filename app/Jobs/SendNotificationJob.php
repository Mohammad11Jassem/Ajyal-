<?php

namespace App\Jobs;

use App\Helpers\PushNotification;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $message;
    // public $fcm_token;
    public $users;
    public $model;

    /**
     * Create a new job instance.
     */
    // public function __construct(array $message, string $fcm_token, $model = null)
    // {
    //     $this->message   = $message;
    //     $this->fcm_token = $fcm_token;
    //     $this->model     = $model;
    // }
    public function __construct(array $message, $users, $model = null)
    {


        $this->message = $message;
        // users ممكن تكون Collection من Eloquent أو Array من IDs
        $this->users   = $users;
        $this->model   = $model;
    }
    /**
     * Execute the job.
     */
    // public function handle(): void
    // {
    //     PushNotification::sendNotification($this->message, $this->fcm_token);

    //     app(NotificationService::class)->store([
    //         'title' => $this->message['title'],
    //         'body'  => $this->message['body'] ,
    //         'model' => $this->model,
    //     ]);
    // }

    public function handle(): void
    {
        $notificationService = app(NotificationService::class);
        foreach ($this->users as $user) {
            // إذا users مرسلة كـ IDs نجيبها من DB
            if (is_numeric($user)) {
                $user = User::find($user);
            }
            // Log::info('user id: '.$user['id']);

            $notti=  $notificationService->store([
                    'user_id'=>$user['id'],
                    'title' => $this->message['title'],
                    'body'  => $this->message['body'],
                    'model' => $this->model,
                ]);
            if (!$user || !$user->fcm_token) {
                // continue;
            }

            // 1. إرسال الإشعار
            // PushNotification::sendNotification($this->message, $user->fcm_token);

                // 2. تخزين الإشعار


        }
    }

}
