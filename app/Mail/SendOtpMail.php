<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use SerializesModels;

    public $otp;

    // تمرير OTP في الـ constructor
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    // بناء الرسالة
    public function build()
    {
        return $this->subject('Your OTP Code')  // موضوع الرسالة
                    ->view('emails.otp')      // اسم الـ View التي ستعرض OTP فيها
                    ->with(['otp' => $this->otp]);  // تمرير OTP في الـ View
    }
}

