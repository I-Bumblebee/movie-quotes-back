<?php

namespace App\Notifications;

use App\Services\EmailVerificationUrlService;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmailBase
{
	protected EmailVerificationUrlService $verificationUrlService;

	public function __construct()
	{
		$this->verificationUrlService = new EmailVerificationUrlService();
	}

	public function verificationUrl($notifiable): string
	{
		return $this->verificationUrlService->createVerificationUrl($notifiable);
	}

	public function toMail($notifiable): MailMessage
	{
		return (new MailMessage)
			->subject('Verify Email Address')
			->view('notifications.email', [
				'url'           => $this->verificationUrl($notifiable),
				'salutation'    => 'Hola ' . $notifiable->name . '!',
				'text'          => 'Thanks for joining Movie quotes! We really appreciate it. Please click the button below to verify your account:',
				'button_text'    => 'Verify now',
			]);
	}
}
