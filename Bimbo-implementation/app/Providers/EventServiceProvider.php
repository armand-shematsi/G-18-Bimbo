<?php

namespace App\Providers;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Events\StaffAutoAssigned;

class EventServiceProvider extends \Illuminate\Foundation\Support\Providers\EventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<int, class-string|null>
     */
    protected $listen = [
        StaffAutoAssigned::class => [
            // No listeners needed, just broadcast
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        //
    }
}
