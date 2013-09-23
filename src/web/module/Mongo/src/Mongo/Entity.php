<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace Mongo;

use MongoId;

class Entity
{
	/**
	 * Array of entity properties
	 * 
	 * @var array
	 */
	protected $properties;

    /** @var \Zend\I18n\Translator\Translator */
    protected $translator;

    /** @var array */
    protected $errorMessages = [];
	
	public function __construct($data = [])
	{
		if (is_object($data)) {
			$data = (array) $data;
		}
		if (!is_array($data)) {
			//throw new \Exception('The data must be an array or object');
		}
		foreach ($data as $key => $value) {
			$this->$key = $value;
		}
	}

    public function setTranslator($translator)
    {
        $this->translator = $translator;
        return $this;
    }

    public function isValid()
    {
        return true;
    }

    public function addErrorMessage($message)
    {
        if ($this->translator) {
            $namespace = explode('\\', get_class($this))[0];
            $message   = $this->translator->translate($message, $namespace);
        }
        $this->errorMessages[] = $message;
    }

    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * Build the parameters for generating the URL on the front-end
     *
     * @param string $separator
     * @return array
     */
    public function buildUrlParams($separator = '___')
    {
        $properties = $this->getProperties();
        return $this->buildParamsRecursively($properties, null, $separator);
    }

    protected function buildParamsRecursively($array, $prefix = null, $separator = '___')
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $data = $this->buildParamsRecursively($value, $prefix ? $prefix . $separator . $key : $key, $separator);
                foreach ($data as $k => $v) {
                    $result[$key . $separator . $k] = $v;
                }
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
	
	/**
	 * Get the entity's properties
	 *
	 * @param array $properties Array of properties. If it is null, the method returns all the properties of entity
	 * @return array
	 */
	public function getProperties($properties = null)
	{
        $pros = [];
        if ($properties) {
            foreach ($properties as $name) {
                $pros[$name] = isset($this->properties[$name]) ? $this->properties[$name] : null;
            }
        } else {
            $pros = $this->properties;
        }

		// Unset the _id attribute
		if (isset($pros['_id']) && ($pros['_id'] instanceof MongoId)) {
            $pros['id'] = (string) $pros['_id'];
			unset($pros['_id']);
		}
		$data = [];
		foreach ($pros as $name => $value) {
            $data[$name] = $this->getPropertiesRecursively($value);
		}
		return $data;
	}

    protected function getPropertiesRecursively($value)
    {
        $return = [];
        switch (true) {
            case ($value instanceof Entity):
                $return = $value->getProperties();
                break;
            case (is_array($value)):
                foreach ($value as $k => $v) {
                    $return[$k] = $this->getPropertiesRecursively($v);
                }
                break;
            default:
                $return = $value;
                break;
        }
        return $return;
    }
	
	/**
	 * Get entity Id
	 * 
	 * @return \MongoId
	 */
	public function getId()
	{
		return (isset($this->properties['_id'])) ? $this->properties['_id'] : '';
	}
	
	// MAGIC METHODS
	
	public function __set($name, $value)
	{
        if ('id' == $name) {
            $this->properties['_id'] = new MongoId($value);
        } else {
		    $this->properties[$name] = $value;
        }
	}
	
	public function __get($name)
	{
		if (array_key_exists($name, $this->properties)) {
			return $this->properties[$name];
		}
		return null;
	}
	
	public function __isset($name)
	{
		return isset($this->properties[$name]);
	}
	
	public function __unset($name)
	{
		if (isset($this->$name)) {
			$this->properties[$name] = null;
		}
	}
}
