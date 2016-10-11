<?php

defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Alarm_Autoresponder
{
    protected $config;

    public static function instance($type = 'email', $config = array())
    {
        $class = 'Alarm_Autoresponder_' . ucfirst($type);
        return new $class($config);
    }

    protected function __construct($config = array())
    {
        $this->_config = $config;
    }

    abstract public function send_message($subject, $message, $mimetype = NULL);

}