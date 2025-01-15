<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\CatMailer;
use Illuminate\Support\Facades\Config;
use Swift_SmtpTransport;
use Swift_Mailer;
use Illuminate\Support\Facades\Mail;
use App\Repositories\GeneralFunctionsRepository;

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
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if(session('department_assigned_mail') != null){
            $smtp_config = CatMailer::where('assigned_to_department',session('department_assigned_mail'))->first();

            if($smtp_config == null){
                $smtp_config = CatMailer::where('assigned_to_department','default')->first();
            }

            config([
                'mail.from' => [
                    'mail.address' => $smtp_config->mail_username,
                    'mail.name' => Config::get('app.name')],
            ]);

            $transport = new Swift_SmtpTransport(
                $smtp_config->mail_host,
                $smtp_config->mail_port,
                $smtp_config->mail_encryption // Puedes agregar el cifrado si es necesario
            );
        
            $transport->setUsername($smtp_config->mail_username);
            //$transport->setPassword($smtp_config->mail_password);
            $transport->setPassword($this->GeneralFunctionsRepository->decryptPasswordCatMailers($smtp_config->mail_password));
        
            // Crear un nuevo mailer con el transporte configurado con la cuenta que obtuvimos segun el usuario
            $mailer = new Swift_Mailer($transport);
        
            // Configurar el mailer de Laravel para usar el nuevo mailer personalizado
            Mail::setSwiftMailer($mailer);

            $this->from($smtp_config->mail_username);
        }
        

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
                    ->markdown("emails.areaNotice");
        }else if($this->general_data['sub_seccion'] == "birthday"){
            return $this->subject($this->general_data['subject'])
                    ->markdown("emails.birthday");
        }else if($this->general_data['sub_seccion'] == "foundation_capsule"){
            return $this->subject($this->general_data['subject'])
                    ->markdown("emails.foundationCapsule");
        }else if($this->general_data['sub_seccion'] == "event"){
            return $this->subject($this->general_data['subject'])
                    ->markdown("emails.template");
        }else if($this->general_data['sub_seccion'] == "benefit"){
            return $this->subject($this->general_data['subject'])
                    ->markdown("emails.template");
        }


    }
}
