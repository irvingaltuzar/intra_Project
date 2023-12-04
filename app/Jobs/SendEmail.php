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
        Mail::to('comunicacion.interna@grupodmi.com.mx')->bcc($this->params['recipient_emails'])->send($sendEmail);
    }
}
