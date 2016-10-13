<?php

defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Sentinel
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

    public static function instance($type, $config = array())
    {
        $class = 'Sentinel_' . ucfirst($type);
        return new $class($config);
    }

    protected function __construct(array $config = array())
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

}

// End Kohana_Sentinel
