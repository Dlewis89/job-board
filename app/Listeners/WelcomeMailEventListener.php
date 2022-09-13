<?php

namespace App\Listeners;

use App\Events\WelcomeMailEvent;
use App\Notifications\WelcomeMailNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WelcomeMailEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\WelcomeMailEvent  $event
     * @return void
     */
    public function handle(WelcomeMailEvent $event)
    {
        $event->user->notify(new WelcomeMailNotification());
    }
}
