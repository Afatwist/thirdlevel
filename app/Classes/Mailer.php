<?php

namespace App\Classes;

class Mailer
{
	public static function email_verify($mailTo, $token, $selector)
	{
		$description = 'Для подтверждения регистрации перейдите по ссылке: 
		http://thirdlevel/users/email_verify/selector=' . \urlencode($selector) . '/token=' . \urlencode($token);
		mail($mailTo, 'Email Verification', $description);
	}

	public static function password_forgot($mailTo, $token, $selector)
	{
		$description = 'Для восстановления пароля перейдите по ссылке:http://thirdlevel/users/password_reset/selector=' . \urlencode($selector) . '/token=' . \urlencode($token);

		mail($mailTo, 'Password forgot', $description);
	}
}
