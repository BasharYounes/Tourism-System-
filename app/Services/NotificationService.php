<?php

use App\Models\User;
use App\Jobs\SendNotificationJob;
class NotificationService
{
    public function send( $id, string $type, array $data = []): void
    {
        SendNotificationJob::dispatch($id, $type, $data)->onQueue('notifications');
    }

   
}