<?php

namespace App\Notifications;

use App\Models\DocumentManagement\File;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class FileUploaded extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected File $file)
    {
        $this->file = $file;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'file_id' => $this->file->id,
            'file_name' => $this->file->file_name,
        ]);
    }

    /**
     * Get the type of the notification being broadcast.
     */
    public function broadcastType(): string
    {
        return 'file.uploaded';
    }
}