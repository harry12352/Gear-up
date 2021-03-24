<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Models\User;

class NewFollower extends Notification {
    /**
     * Create a new notification instance.
     *
     * @param User $user
     */
    public function __construct(User $user) {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array {
        return [
            'followerUserID' => $this->user->id,
            'followedUserID' => $notifiable->id
        ];
    }
}
