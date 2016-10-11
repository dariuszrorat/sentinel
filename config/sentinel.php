<?php

defined('SYSPATH') or die('No direct access allowed.');

return array(
    'default' => array(
        'directories' => array(
            'scanned' => array(
            ),
            'backup' => APPPATH . 'backup',
            'logs' => APPPATH . 'logs',
            'quarantine' => APPPATH . 'quarantine'
        ),
        'ignored' => array(
            'directories' => array(
                APPPATH . 'cache',
                APPPATH . 'logs'
            ),
            'files' => array(
                '.svn',
                '.git',
                '.gitignore'
            )
        ),
        'compression' => array(
            'type' => 'zip',
            'include_subfolders' => true,
            'params' => array()
        ),
        'inspection' => array(
            'checksum_storage' => array(
                'type' => 'file',
                'directory' => APPPATH . 'inspection',
            ),
            'self_inspection' => true,
            'on_detection' => Sentinel::NOTHING,
            'caching'      => false,
            'cache_life'   => 1209600,
        ),
        'quarantine' => array(
            'maxlife' => 604800,
            'gc' => 500
        ),
        'autoresponder' => array(
            'driver' => 'email',
            'enabled' => false,
            'project_name' => 'Kohana',
            'email' => array(
                'sender'    => 'sender@domain',
                'recipient' => 'recipient@domain',
                'mime_type' => 'text/html',
            ),
            'sms' => array(
                'recipient' => 'your phone number',
            ),
        ),
    )
);
