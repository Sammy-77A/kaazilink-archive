<?php

namespace App\Notifications;

use App\Models\KycSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class KycPendingReviewNotification extends Notification
{
    use Queueable;

    public KycSubmission $submission;

    public function __construct(KycSubmission $submission)
    {
        $this->submission = $submission;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $user = $this->submission->user;
        $userName = $user ? $user->name : 'A provider';
        $userEmail = $user ? $user->email : 'N/A';

        $mail = (new MailMessage)
            ->subject("Action Required: New KYC Verification Pending Review — {$userName}")
            ->greeting("Habari Admin,")
            ->line("A provider has submitted their verification documents for review on Kaazilink.")
            ->line("**Provider Name:** {$userName}")
            ->line("**Email:** {$userEmail}");

        if ($user && $user->id_number) {
            $mail->line("**National ID:** {$user->id_number}");
        } else if ($user) {
            $mail->line("**Account ID:** #{$user->id}");
        }

        $mail->line('Submitted files: National ID (Front & Back), KRA PIN.');

        return $mail
            ->action('Review KYC Submission', route('admin.kyc.show', $this->submission->id))
            ->line('Please inspect the submitted documents and approve or reject the application.')
            ->salutation("Kaazilink Admin System");
    }
}
