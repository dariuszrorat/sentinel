<?php

defined('SYSPATH') or die('No direct script access.');

class Kohana_Filesystem_Archiver_Zip extends Filesystem_Archiver
{

    public function __construct()
    {
        
    }
    
    public function compress($source, $destination)
    {
        if (!extension_loaded('zip') || empty($source))
        {
            return false;
        }

        if (file_exists($destination))
        {
            unlink($destination);
        }

        $zip = new ZipArchive();

        if (!$zip->open($destination, ZIPARCHIVE::CREATE))
        {
            return false;
        }

        foreach ($source as $src)
        {
            $file = $src['file'];
            $zip->addFile($file);
        }
        return $zip->close();
    }
    
    public function extract($source, $destination)
    {
        $zip = new ZipArchive;
        if ($zip->open($source) === TRUE)
        {
           $zip->extractTo($destination);
           $zip->close();
           return true;
        }
        return false;
    }

}
