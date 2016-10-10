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
    protected $_checksums = array();
    protected $_changes = array();
    
    protected $_id = 0;

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
    
    public function checksums()
    {
        return $this->_checksums;
    }
    
    public function changes()
    {
        return $this->_changes;
    }
    
    public function check()
    {
        $checkdir = $this->_config['inspection']['checksum_storage']['directory'];
        $checkfile = $checkdir . DIRECTORY_SEPARATOR . sha1(serialize($this->_config)) . '.ser';
        if (realpath($checkfile))
        {
            $checksums = unserialize(file_get_contents($checkfile));
            foreach ($checksums as $item)
            {
                $id = $item['id'];
                $file = $item['file'];
                $checksum = $item['checksum'];
                $fchecksum = sha1_file($file);
                if ($checksum !== $fchecksum)
                {
                    $this->_changes[] = array(
                        'id' => $id,
                        'file' => $file,
                        'original_checksum' => $checksum,
                        'new_checksum' => $fchecksum
                    );
                }
            }
            return empty($this->_changes);
        }
        return false;
    }

    public function update_checksum($id)
    {
        $checkdir = $this->_config['inspection']['checksum_storage']['directory'];
        $checkfile = $checkdir . DIRECTORY_SEPARATOR . sha1(serialize($this->_config)) . '.ser';
        
        if (realpath($checkfile))
        {
            $checksums = unserialize(file_get_contents($checkfile));            
            for ($i = 0, $total = count($checksums); $i < $total; $i++)
            {
                if ($id == $checksums[$i]['id'])
                {
                    $file = $checksums[$i]['file'];                                          
                    $checksums[$i]['checksum'] = sha1_file($file);                    
                    file_put_contents(realpath($checkfile), serialize($checksums), LOCK_EX);                    
                    return true;
                }                
            }
            return false;    
        }
        return false;
    }
    
    public function update()
    {
        $this->_id = 0;
        $directories = $this->_config['directories']['scanned'];
        
        foreach ($directories as $dir)
        {
            $finfo = new SplFileInfo($dir);            
            $this->_calculate_checksums($finfo);
            $outdir = $this->_config['inspection']['checksum_storage']['directory'];
            $outfile = $outdir . DIRECTORY_SEPARATOR . sha1(serialize($this->_config)) . '.ser';
            file_put_contents($outfile, serialize($this->_checksums), LOCK_EX);
        }
        return;
    }
    
    protected function _calculate_checksums(SplFileInfo $file)
    {
			// If is file
			if ($file->isFile())
			{
				try
				{
                    $fpath = $file->getRealPath();
                    $checksum = sha1_file($fpath);
                    $this->_id += 1;
                    $this->_checksums[] = array(       
                        'id' => $this->_id,
                        'file' => $fpath,
                        'checksum' => $checksum
                        );
				}
				catch (ErrorException $e)
				{
					// Catch any delete file warnings
					if ($e->getCode() === E_WARNING)
					{
						throw new Sentinel_Exception(__METHOD__.' failed to open file : :file', array(':file' => $file->getRealPath()));
					}
				}
			}
			// Else, is directory
			elseif ($file->isDir())
			{
				// Create new DirectoryIterator
				$files = new DirectoryIterator($file->getPathname());

				// Iterate over each entry
				while ($files->valid())
				{
					// Extract the entry name
					$name = $files->getFilename();

					// If the name is not a dot
					if ($name != '.' AND $name != '..')
					{
						// Create new file resource
						$fp = new SplFileInfo($files->getRealPath());
						// Delete the file
						$this->_calculate_checksums($fp);
					}

					// Move the file pointer on
					$files->next();
				}

			}
			else
			{
				// We get here if a file has already been deleted
				return;
			}		        
    }

}
// End Kohana_Cache
