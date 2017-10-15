<?php

namespace App\Listeners;

use App\Events\BusinessHasBeenApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;

class EmailBusinessCredentials implements ShouldQueue
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
     * @param  BusinessHasBeenApproved  $event
     * @return void
     */
    public function handle(BusinessHasBeenApproved $event)
    {
        $business = $event->business;

         $this->mailer->send(
             'emails.business.credentials',
             ['business' => $business, 'password' => decrypt($event->password)],
             function ($message) use ($business) {
                 $message->to($business->login_email, $business->name);
                 $message->subject('Chúc mừng, đơn đăng ký tài khoản của Quý doanh nghiệp đã được chấp nhận');
             }
         );
    }
}
