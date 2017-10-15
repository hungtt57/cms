<?php

namespace App\Listeners;

use App\Events\CollaboratorHasBeenAdded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;

class EmailCollaboratorCredentials implements ShouldQueue
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
     * @param  CollaboratorHasBeenAdded  $event
     * @return void
     */
    public function handle(CollaboratorHasBeenAdded $event)
    {
//        $collaborator = $event->collaborator;
//        $transport = $this->mailer->getSwiftMailer()->getTransport();
//        $transport->setHost('smtp.gmail.com');
//        $transport->setPort(465);
//        $transport->setUsername('ctv.runtime.mobi@gmail.com');
//        $transport->setPassword('vrkgrsgxtaoulqni');
//        $transport->setEncryption('ssl');
//
//        $this->mailer->send(
//            'emails.collaborator.credentials',
//            ['collaborator' => $collaborator, 'password' => decrypt($event->password)],
//            function ($message) use ($collaborator) {
//                $message->from('ctv.runtime.mobi@gmail.com', 'Runtime Mobi');
//                $message->to($collaborator->email, $collaborator->name);
//                $message->subject('Thông tin đăng nhập hệ thống Runtime Mobi Công tác viên');
//            }
//        );
    }
}
