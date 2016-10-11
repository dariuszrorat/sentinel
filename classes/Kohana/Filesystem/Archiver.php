<?php

defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Filesystem_Archiver
{
    public static $default = 'zip';
    
    public static function instance($type = NULL)
    {
        if ($type === NULL)
        {
            $type = Filesystem_Archiver::$default;
        }
        $class = 'Filesystem_Archiver_' . ucfirst($type);
        return new $class;
    }
    
    abstract public function compress($source, $destination);
    
    abstract public function extract($source, $destination);
}