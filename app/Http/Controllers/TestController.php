<?php

namespace App\Http\Controllers;

use App\Services\SendEmailService;
use Illuminate\Http\Request;

class TestController extends Controller
{
	private $sendEmail;

	public function __construct(SendEmailService $sendEmail)
	{
		$this->sendEmail = $sendEmail;
	}

    public function test()
    {
        return $this->sendEmail->test();
    }
}
