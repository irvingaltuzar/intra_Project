<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmails extends Mailable
{
    use Queueable, SerializesModels;

    public array $general_data = [];
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        //
        $this->general_data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->general_data['sub_seccion'] == "communication"){
            return $this->subject($this->general_data['subject'])
                    ->markdown("emails.template");
        }else if($this->general_data['sub_seccion'] == "promotions"){
            return $this->subject($this->general_data['subject'])
                    ->markdown("emails.promotions");
        }else if($this->general_data['sub_seccion'] == "condolences"){
            return $this->subject($this->general_data['subject'])
                    ->markdown("emails.condolences");
        }else if($this->general_data['sub_seccion'] == "internal_posting"){
            return $this->subject($this->general_data['subject'])
                    ->markdown("emails.internalPosting");
        }else if($this->general_data['sub_seccion'] == "poll"){
            return $this->subject($this->general_data['subject'])
                    ->markdown("emails.poll");
        }else if($this->general_data['sub_seccion'] == "area_notice"){
            return $this->subject($this->general_data['subject'])
                    ->markdown("emails.areaNotice");
        }else if($this->general_data['sub_seccion'] == "policy"){
            return $this->subject($this->general_data['subject'])
                    ->markdown("emails.policy");
        }else if($this->general_data['sub_seccion'] == "birthday"){
            return $this->subject($this->general_data['subject'])
                    ->markdown("emails.birthday");
        }else if($this->general_data['sub_seccion'] == "foundation_capsule"){
            return $this->subject($this->general_data['subject'])
                    ->markdown("emails.foundationCapsule");
        }


    }
}
