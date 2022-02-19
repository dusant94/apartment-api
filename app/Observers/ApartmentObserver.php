<?php

namespace App\Observers;

use App\Models\Apartment;
use App\Models\Subscription;
use App\Notifications\SubscriptionNotification;
use Illuminate\Support\Facades\Notification;

class ApartmentObserver
{

    /**
     * Handle the Apartment "updated" event.
     *
     * @param  \App\Models\Apartment  $product
     * @return void
     */
    public function updated(Apartment $apartment)
    {
        if ($apartment->wasChanged('price')) {
            $subscriptions = Subscription::where('apartment_id', $apartment->id)->get();
            foreach($subscriptions as $subscription){
                if($apartment->price < $subscription->price_limit){
                    Notification::send($subscription->user, new SubscriptionNotification($apartment));
                }
            }
        }
    }
}
