<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotEmail extends Mailable {

	use Queueable, SerializesModels;

	/**
	 * The demo object instance.
	 *
	 * @var Demo
	 */
	public $demo;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($demo) {

		$this->demo = $demo;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {

		$subject = $this->demo['subject'];

		return $this->view('emails.forgot_password', $this->demo)->subject($subject);

	}
}
