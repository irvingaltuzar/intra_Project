<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendEmails;
use Illuminate\Support\Facades\Mail;
use App\Models\CatMailer;
use Illuminate\Support\Facades\Config;
use Swift_SmtpTransport;
use Swift_Mailer;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $params = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $_general_data)
    {
        $this->params = $_general_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sendEmail = new SendEmails($this->params);

        //revisamos que hay un email asignado para el departamento del usuario
        if(session('department_assigned_mail') != null){
            // obtenemos la info del nuevo correo
            $smtp_config = CatMailer::where('assigned_to_department',session('department_assigned_mail'))->first();
            if($smtp_config == null){
                $smtp_config = CatMailer::where('assigned_to_department','default')->first();
            }
        }else{
            $smtp_config = CatMailer::where('assigned_to_department','default')->first();
        }

        Mail::to($smtp_config->mail_username)->bcc($this->params['recipient_emails'])->send($sendEmail);

        
    }
}
