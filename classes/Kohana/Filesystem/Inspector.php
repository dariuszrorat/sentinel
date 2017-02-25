<?php

defined('SYSPATH') or die('No direct script access.');

class Kohana_Filesystem_Inspector
{

    protected $_config;
    protected $_index = -1;
    protected $_registered = array();
    protected $_unregistered = array();

    public static function factory(array $config)
    {
        return new Filesystem_Inspector($config);
    }

    public function __construct(array $config)
    {
        $this->_config = $config;
    }

    public function registered()
    {
        return $this->_registered;
    }

    public function unregistered()
    {
        return $this->_unregistered;
    }

    public function register_file(SplFileInfo $file)
    {
        if ($file->isFile())
        {
            try
            {
                $fpath = $file->getRealPath();
                if (!in_array($file->getFilename(), $this->_config['filesystem']['ignored']['files']))
                {
                    $checksum = hash_file('sha512', $fpath);
                    $this->_index += 1;
                    $this->_registered[] = array(
                        'index' => $this->_index,
                        'file' => $fpath,
                        'checksum' => $checksum
                    );
                }
            } catch (ErrorException $e)
            {
                if ($e->getCode() === E_WARNING)
                {
                    throw new Sentinel_Exception(__METHOD__ . ' failed to open file : :file', array(':file' => $file->getRealPath()));
                }
            }
        } elseif ($file->isDir())
        {
            if (!in_array($file->getPathname(), $this->_config['filesystem']['ignored']['directories']))
            {
                $files = new DirectoryIterator($file->getPathname());

                while ($files->valid())
                {
                    $name = $files->getFilename();

                    if ($name != '.' AND $name != '..')
                    {
                        $fp = new SplFileInfo($files->getRealPath());
                        $this->register_file($fp);
                    }

                    $files->next();
                }
            }
        } else
        {
            return;
        }
    }

    public function find_unregistered_file(SplFileInfo $file, array $checksums)
    {
        if ($file->isFile())
        {
            try
            {
                $fpath = $file->getRealPath();
                if (!in_array($file->getFilename(), $this->_config['filesystem']['ignored']['files']) && !$this->in_list($fpath, $checksums))
                {
                    $checksum = hash_file('sha512', $fpath);
                    $this->_index += 1;
                    $this->_unregistered[] = array(
                        'index' => $this->_index,
                        'file' => $fpath,
                        'checksum' => $checksum
                    );
                }
            } catch (ErrorException $e)
            {
                if ($e->getCode() === E_WARNING)
                {
                    throw new Sentinel_Exception(__METHOD__ . ' failed to open file : :file', array(':file' => $file->getRealPath()));
                }
            }
        } elseif ($file->isDir())
        {
            if (!in_array($file->getPathname(), $this->_config['filesystem']['ignored']['directories']))
            {
                $files = new DirectoryIterator($file->getPathname());
                while ($files->valid())
                {
                    $name = $files->getFilename();
                    if ($name != '.' AND $name != '..')
                    {
                        $fp = new SplFileInfo($files->getRealPath());
                        $this->find_unregistered_file($fp, $checksums);
                    }
                    $files->next();
                }
            }
        } else
        {
            return;
        }
    }

    public function find_registered_files()
    {
        $checkdir = $this->_config['filesystem']['inspection']['checksum_storage']['directory'];
        $checkfile = $checkdir . DIRECTORY_SEPARATOR . 'registered.ser';
        $registered = array();

        if (realpath($checkfile))
        {
            try
            {
                $registered = unserialize(file_get_contents($checkfile));
            } catch (ErrorException $e)
            {
                if ($e->getCode() === E_WARNING)
                {
                    throw new Sentinel_Exception(__METHOD__ . ' failed to unserialize from file : :file', array(':file' => $file->getRealPath()));
                }
            }
        }

        return $registered;
    }

    public function save($file, $data)
    {
        try
        {
            $outdir = $this->_config['filesystem']['inspection']['checksum_storage']['directory'];
            $outfile = $outdir . DIRECTORY_SEPARATOR . $file;
            file_put_contents($outfile, serialize($data), LOCK_EX);
            return true;
        } catch (ErrorException $e)
        {
            if ($e->getCode() === E_WARNING)
            {
                throw new Sentinel_Exception(__METHOD__ . ' failed to serialize to file : :file', array(':file' => $file->getRealPath()));
            }
        }
    }

    public function in_list($file, $list)
    {
        foreach ($list as $item)
        {
            if ($item['file'] == $file)
            {
                return true;
            }
        }
        return false;
    }
}
