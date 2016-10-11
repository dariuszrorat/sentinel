<?php defined('SYSPATH') OR die('No direct script access.');

class File extends Kohana_File
{
    public static function combine($directory, $filename)
    {
        return join(DIRECTORY_SEPARATOR, array($directory, $filename));
    }
}
