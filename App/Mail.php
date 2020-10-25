<?php

namespace App;

use App\Config;
use Mailgun\Mailgun;

/**
 * Mail
 *
 * PHP version 7.0
 */
class Mail
{

    /**
     * Send a message
     *
     * @param string $to Recipient
     * @param string $subject Subject
     * @param string $text Text-only content of the message
     * @param string $html HTML content of the message
     *
     * @return mixed
     */
    public static function send($to, $subject, $text, $html)
    {
		$mg = Mailgun::create(Config::MAILGUN_API_KEY);
        $mg->messages()->send(Config::MAILGUN_DOMAIN, [
								   'from'    => 'mglowinski93@gmail.com',
                                   'to'      => $to,
                                   'subject' => $subject,
                                   'text'    => $text,
                                   'html'    => $html
								   ]);
    }
}
