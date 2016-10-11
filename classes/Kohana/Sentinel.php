<?php

defined('SYSPATH') or die('No direct script access.');

class Kohana_Sentinel
{

    const NOTHING = 00;
    const QUARANTINE = 10;
    const REPAIR = 20;
    const DELETE = 30;

    public static $default = 'default';

    /**
     * @var  Config
     */
    protected $_config = array();
    protected $_registered = array();
    protected $_modified = array();
    protected $_unregistered = array();
    protected $_deleted = array();

    public static function instance($config = array())
    {
        return new Sentinel($config);
    }

    public function __construct(array $config = array())
    {
        // Overwrite system defaults with application defaults
        $this->_config = $this->config_group() + $this->_config;

        $this->setup($config);
    }

    public function config_group($group = NULL)
    {
        if ($group === NULL)
        {
            $group = Sentinel::$default;
        }
        // Load the config file
        $config_file = Kohana::$config->load('sentinel');

        // Initialize the $config array
        $config['group'] = (string) $group;

        // Recursively load requested config groups
        while (isset($config['group']) AND isset($config_file->$config['group']))
        {
            // Temporarily store config group name
            $group = $config['group'];
            unset($config['group']);

            // Add config group values, not overwriting existing keys
            $config += $config_file->$group;
        }

        // Get rid of possible stray config group names
        unset($config['group']);

        // Return the merged config group settings
        return $config;
    }

    public function setup(array $config = array())
    {
        if (isset($config['group']))
        {
            // Recursively load requested config groups
            $config += $this->config_group($config['group']);
        }
        // Overwrite the current config settings
        $this->_config = $config + $this->_config;

        // Chainable method
        return $this;
    }

    /**
     * Overload the __clone() method to prevent cloning
     *
     * @return  void
     * @throws  Sentinel_Exception
     */
    final public function __clone()
    {
        throw new Sentinel_Exception('Cloning of Kohana_Sentinel objects is forbidden');
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
        $directories = $this->_config['directories']['scanned'];
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
                $fchecksum = sha1_file($file);
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
        return !empty($this->_modified);
    }

    public function find_unregistered()
    {
        $inspector = new Filesystem_Inspector($this->_config);
        $registered = $inspector->find_registered_files();
        $directories = $this->_config['directories']['scanned'];

        foreach ($directories as $dir)
        {
            $fileinfo = new SplFileInfo($dir);
            $inspector->find_unregistered_file($fileinfo, $registered);
        }

        $this->_unregistered = $inspector->unregistered();
        if (!empty($this->_unregistered))
        {
            return $inspector->save('unregistered.ser', $this->_unregistered);
        }
        return false;
    }

    public function find_deleted()
    {
        $inspector = new Filesystem_Inspector($this->_config);
        $registered = $inspector->find_registered_files();
        $directories = $this->_config['directories']['scanned'];

        foreach ($registered as $item)
        {
            if (!realpath($item['file']))
            {
                $this->_deleted[] = $item;
            }
        }
        if (!empty($this->_deleted))
        {
            return $inspector->save('deleted.ser', $this->_deleted);
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
            $registered[$index]['checksum'] = sha1_file($file);
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
            $backupdir = $this->_config['directories']['backup'];
            $type = $this->_config['compression']['type'];
            $archiver = Filesystem_Archiver::instance($type);
            $dest = sprintf('%s.zip', File::combine($backupdir, date('Y_m_d')));
            $result = $archiver->compress($registered, $dest);
            return $result;
        }
        return false;
    }

}

// End Kohana_Sentinel
