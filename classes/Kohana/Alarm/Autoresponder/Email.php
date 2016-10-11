<?php

defined('SYSPATH') or die('No direct script access.');

class Kohana_Alarm_Autoresponder_Email extends Alarm_Autoresponder
{

    public function __construct($config)
    {
        parent::__construct($config);
    }

    public function send_message($subject, $message, $mimetype = NULL)
    {
        if ($mimetype === NULL)
        {
            $mimetype = $this->_config['autoresponder']['email']['mime_type'];
        }

        Email::factory($subject, $message, $mimetype)
                    ->to($this->_config['autoresponder']['email']['recipient'])
                    ->from($this->_config['autoresponder']['email']['sender'])
                    ->send();
    }
}
