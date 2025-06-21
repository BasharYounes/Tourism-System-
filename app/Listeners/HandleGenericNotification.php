<?php

namespace App\Listeners;

use App\Events\GenericNotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use NotificationService;


class HandleGenericNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(GenericNotificationEvent $event)
    {
        $service = new NotificationService();
        $service->send(
            id: $event->id,
            type: $event->type,
            data: $event->data
        );

        
    }

        public $tries = 3;

        public $backoff = [5, 10, 15];
}
