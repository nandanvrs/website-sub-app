<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\PostPublishedEvent;
use App\Models\MailLog;
use App\Models\SubscribedWebsite;
use Illuminate\Support\Carbon;

class PostEventListener implements ShouldQueue
{
    /**
     * Handle PostPublishedEvent event.
     *
     * @param  object  $event
     * @return void
     */
    public function onPublished(PostPublishedEvent $event)
    {
        $post = $event->post;
        if ($post->status == 'published') {
            $subscribers = SubscribedWebsite::where('website_id', $post->website_id)->pluck('subscriber_id')->toArray();
            $logs = [];
            foreach ($subscribers as $subscriber) {
                $logs[] = ['post_id' => $post->id, 'subscriber_id' => $subscriber, 'created_at' => Carbon::now()];
            }
            MailLog::insert($logs);
        }
    }



    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        // On Publish Post
        $events->listen(
            PostPublishedEvent::class,
            'App\Listeners\PostEventListener@onPublished'
        );
    }
}
