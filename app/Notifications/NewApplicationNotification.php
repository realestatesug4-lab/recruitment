<?php

namespace App\Notifications;

use App\Domain\Applications\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplicationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Application $application,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $candidateName = $this->application->seekerProfile?->name ?? 'A candidate';
        $jobTitle = $this->application->job?->title ?? 'a role';

        return (new MailMessage)
            ->subject("New application from {$candidateName}")
            ->greeting("Hello {$notifiable->name}")
            ->line("**{$candidateName}** has applied for **{$jobTitle}**.")
            ->line('Review their profile and move them through your hiring pipeline.')
            ->action('Review application', route('employer.applications.show', $this->application->uuid))
            ->line('CraneLinks — One Click Away');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_application',
            'application_uuid' => $this->application->uuid,
            'job_title' => $this->application->job?->title,
            'candidate_name' => $this->application->seekerProfile?->name ?? 'Candidate',
            'message' => 'applied for ' . ($this->application->job?->title ?? 'a role'),
            'url' => route('employer.applications.show', $this->application->uuid),
        ];
    }
}
