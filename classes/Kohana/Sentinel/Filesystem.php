<?php

defined('SYSPATH') or die('No direct script access.');

class Kohana_Sentinel_Filesystem extends Sentinel
{
    public static $default = 'default';

    /**
     * @var  Config
     */
    protected $_config = array();
    protected $_registered = array();
    protected $_modified = array();
    protected $_unregistered = array();
    protected $_deleted = array();

    public function __construct(array $config = array())
    {
        parent::__construct($config);
    }

    public function registered()
    {
        return $this->_registered;
    }

    public function modified()
    {
        return $this->_modified;
    }

    public function unregistered()
    {
        return $this->_unregistered;
    }

    public function deleted()
    {
        return $this->_deleted;
    }

    public function register_files()
    {
        $directories = $this->_config['filesystem']['directories']['scanned'];
        $inspector = new Filesystem_Inspector($this->_config);

        foreach ($directories as $dir)
        {
            $fileinfo = new SplFileInfo($dir);
            $inspector->register_file($fileinfo);
        }
        $this->_registered = $inspector->registered();
        if (!empty($this->_registered))
        {
            return $inspector->save('registered.ser', $this->_registered);
        }
        return false;
    }

    public function find_modified()
    {
        $registered = Filesystem_Inspector::factory($this->_config)
                ->find_registered_files();

        foreach ($registered as $item)
        {
            $file = $item['file'];

            if (realpath($file))
            {
                $index = $item['index'];
                $checksum = $item['checksum'];
                $fchecksum = hash_file('sha512', $file);
                if ($checksum !== $fchecksum)
                {
                    $this->_modified[] = array(
                        'index' => $index,
                        'file' => $file,
                        'original_checksum' => $checksum,
                        'new_checksum' => $fchecksum
                    );
                }
            }
        }
        if (!empty($this->_modified))
        {
            $this->_run_autoresponder('List of modified files', $this->_modified, true);
            return true;
        }

        return false;

    }

    public function find_unregistered()
    {
        $inspector = new Filesystem_Inspector($this->_config);
        $registered = $inspector->find_registered_files();
        $directories = $this->_config['filesystem']['directories']['scanned'];

        foreach ($directories as $dir)
        {
            $fileinfo = new SplFileInfo($dir);
            $inspector->find_unregistered_file($fileinfo, $registered);
        }

        $this->_unregistered = $inspector->unregistered();
        if (!empty($this->_unregistered))
        {
            $result = $inspector->save('unregistered.ser', $this->_unregistered);
            $this->_run_autoresponder('List of unregistered files', $this->_unregistered);
            return $result;
        }
        return false;
    }

    public function find_deleted()
    {
        $inspector = new Filesystem_Inspector($this->_config);
        $registered = $inspector->find_registered_files();
        $directories = $this->_config['filesystem']['directories']['scanned'];

        foreach ($registered as $item)
        {
            if (!realpath($item['file']))
            {
                $this->_deleted[] = $item;
            }
        }
        if (!empty($this->_deleted))
        {
            $result = $inspector->save('deleted.ser', $this->_deleted);
            $this->_run_autoresponder('List of deleted files', $this->_deleted);
            return $result;
        }
        return;
    }

    public function update_checksum($index)
    {
        $inspector = new Filesystem_Inspector($this->_config);
        $registered = $inspector->find_registered_files();

        if (!empty($registered))
        {
            $file = $registered[$index]['file'];
            $registered[$index]['checksum'] = hash_file('sha512', $file);
            return $inspector->save('registered.ser', $registered);
        }
        return false;
    }

    public function backup()
    {
        $inspector = new Filesystem_Inspector($this->_config);
        $registered = $inspector->find_registered_files();
        if (!empty($registered))
        {
            $backupdir = $this->_config['filesystem']['directories']['backup'];
            $type = $this->_config['filesystem']['compression']['type'];
            $archiver = Filesystem_Archiver::instance($type);
            $dest = sprintf('%s.zip', File::combine($backupdir, date('Y_m_d')));
            $result = $archiver->compress($registered, $dest);
            return $result;
        }
        return false;
    }

    protected function _run_autoresponder($title, $data, $modified = false)
    {
            $enable_autoresponder = $this->_config['autoresponder']['enabled'];
            if ($enable_autoresponder === true)
            {
                $driver = $this->_config['autoresponder']['driver'];
                $autoresponder = Alarm_Autoresponder::instance($driver, $this->_config);
                $results = "";
                foreach ($data as $item)
                {
                    if ($modified === true)
                    {
                        $results .= "<P><B>File: </B>" . $item['file']
                          . "<BR><B>Original checksum: </B>" . $item['original_checksum']
                          . "<BR><B>New checksum: </B>" . $item['new_checksum']
                          . "</P>";
                    }
                    else
                    {
                        $results .= "<P><B>File: </B>" . $item['file']
                          . "<BR><B>Checksum: </B>" . $item['checksum']
                          . "</P>";
                    }
                }

                $message = View::factory('autoresponder/' . $driver . '/alert')
                    ->set('title', $title)
                    ->set('results', $results);
                $project_name = $this->_config['autoresponder']['project_name'];
                $title .= ' in project: ' . $project_name;
                $autoresponder->send_message($title, $message);
            }

    }

}

// End Kohana_Sentinel
