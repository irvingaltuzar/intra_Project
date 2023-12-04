<?php

namespace App\Jobs;

use App\Mail\TestingMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $email_list, $data, $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email_list, $data, $type)
    {
        $this->email_list = $email_list;
		$this->data = $data;
		$this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email_list)->send(new TestingMail($this->data));
    }
}
