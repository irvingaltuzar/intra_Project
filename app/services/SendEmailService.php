<?php

namespace App\Services;
class SendEmailService
{
	public function test()
	{
		$mails = [
			'carlos.montejo@grupodmi.com.mx',
            'eladio.perez@grupodmi.com.mx'
		];

        $collect = [];

		dispatch(new \App\Jobs\SendEmailJob($mails, $collect, "Food"))->afterResponse();
	}
}
