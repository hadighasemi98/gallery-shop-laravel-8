<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOrderedImages extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $images ;
    private $user ;

    public function __construct(User $user , array $images )
    {
        $this->images = $images;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('mail.SendOrderedImages')->with([
            'user' => $this->user,
        ]);

        foreach( $this->images as $filePath ){
            $email->attach(storage_path('app/local_storage/'.$filePath) );
        }

        return $email ;
    }
}
