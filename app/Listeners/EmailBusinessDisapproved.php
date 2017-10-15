<?php

namespace App\Listeners;

use App\Events\BusinessWasDisapproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;
use Log;

class EmailBusinessDisapproved implements ShouldQueue
{
    protected $mailer;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  BusinessWasDisapproved  $event
     * @return void
     */
    public function handle(BusinessWasDisapproved $event)
    {
        $business = $event->business;

        $this->mailer->send(
            'emails.business.disapproved',
            ['business' => $business, 'reason' => $event->reason],
            function ($message) use ($business) {
                $message->to($business->login_email, $business->name);
                $message->subject('Rất tiếc, yêu cầu đăng ký tài khoản của Quý doanh nghiệp đã không được chấp nhận');
            }
        );
    }
}
