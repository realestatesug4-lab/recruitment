<?php

namespace App\Notifications;

use App\Domain\Applications\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationConfirmationNotification extends Notification implements ShouldQueue
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
        $jobTitle = $this->application->job?->title ?? 'a role';
        $company = $this->application->job?->company?->name ?? 'the company';

        return (new MailMessage)
            ->subject("Application submitted: {$jobTitle}")
            ->greeting("Hi {$notifiable->name}")
            ->line("Your application for **{$jobTitle}** at **{$company}** has been received.")
            ->line('We will notify you as soon as the hiring team updates your status.')
            ->action('Track your application', route('seeker.applications.progress'))
            ->line('CraneLinks — One Click Away');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'application_submitted',
            'application_uuid' => $this->application->uuid,
            'job_title' => $this->application->job?->title,
            'company_name' => $this->application->job?->company?->name,
            'message' => 'Application submitted for ' . ($this->application->job?->title ?? 'a role'),
            'url' => route('seeker.applications.progress'),
        ];
    }
}
