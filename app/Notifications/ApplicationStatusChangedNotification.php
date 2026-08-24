<?php

namespace App\Notifications;

use App\Domain\Applications\Models\Application;
use App\Domain\Applications\Enums\ApplicationStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Application $application,
        public ApplicationStatus $newStatus,
        public ?string $note = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $jobTitle = $this->application->job?->title ?? 'a role';
        $company = $this->application->job?->company?->name ?? 'the company';
        $statusLabel = $this->statusLabel();

        $mail = (new MailMessage)
            ->subject("Application update: {$jobTitle}")
            ->greeting("Hi {$notifiable->name}")
            ->line("Your application for **{$jobTitle}** at **{$company}** has been updated to **{$statusLabel}**.");

        if ($this->note) {
            $mail->line("Note from the hiring team: _{$this->note}_");
        }

        return $mail
            ->action('View your application', route('seeker.applications.progress'))
            ->line('CraneLinks — One Click Away');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'status_changed',
            'application_uuid' => $this->application->uuid,
            'job_title' => $this->application->job?->title,
            'company_name' => $this->application->job?->company?->name,
            'new_status' => $this->newStatus->value,
            'status_label' => $this->statusLabel(),
            'message' => "Status changed to {$this->statusLabel()} for " . ($this->application->job?->title ?? 'a role'),
            'note' => $this->note,
            'url' => route('seeker.applications.progress'),
        ];
    }

    private function statusLabel(): string
    {
        return match ($this->newStatus) {
            ApplicationStatus::SHORTLISTED => 'Shortlisted',
            ApplicationStatus::INTERVIEW => 'Interview Scheduled',
            ApplicationStatus::HIRED => 'Hired',
            ApplicationStatus::REJECTED => 'Not Selected',
            default => ucfirst($this->newStatus->value),
        };
    }
}
