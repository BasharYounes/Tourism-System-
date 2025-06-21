<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kreait\Firebase\Messaging\CloudMessage;


class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $type;
    protected $data;

    public function __construct( $id, string $type, array $data)
    {
        $this->id = $id;
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $content = $this->getTemplate($this->type, $this->data);

        $this->sendFCM($this->id, $content);

        $this->saveToDatabase($this->id, $content);
    }

    private function getTemplate(string $type, array $data): array
    {
        $template = config("notifications.templates.$type");

        return [
            'title' => $this->replacePlaceholders($template['title'], $data),
            'body' => $this->replacePlaceholders($template['body'], $data)
        ];    
    }

    protected function replacePlaceholders(string $text, array $data): string
    {
        foreach ($data as $key => $value) {
            $text = str_replace("{{$key}}", $value, $text);
        }
        return $text;
    }

    private function sendFCM( $id, array $content): void
    {
        try {
            $messaging = app('firebase.messaging');

            $user = User::find($id);

            $message = CloudMessage::withTarget('token', $user->fcm_token)
                ->withNotification($content);
            $messaging->send($message);
        } catch (\Exception $e) {
            \Log::error('فشل إرسال الإشعار: ' . $e->getMessage());
        }
    }
    

    private function saveToDatabase( $id, array $content): void
    {
        Notification::create([
            'user_id' => $id,
            'title' => $content['title'],
            'body' => $content['body']
        ]);
    }
}
