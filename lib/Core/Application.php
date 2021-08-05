<?php

namespace Lib\Core;

class Application
{
    protected static $instance = null;

    protected static $vars = [];

    protected $containers = [];

    protected static $singleton = [];

    protected $providers = [];

    protected $subscribers = [];

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new static;
        }
        return self::$instance;
    }

    public function make($name, $Class = null)
    {
        if (!is_null($Class))
            self::setContainer($name, $Class);
        return self::getContainer($name);
    }

    public function provider($name, $Class = null)
    {
        if (!is_null($Class))
            $this->setProvider($name, $Class);
        return $this->getProvider($name);
    }

    /**
     * @param $key
     * @param $value
     */
    public function setVar($key, $value)
    {
        self::$vars[$key] = $value;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function getVar($key, $default = null)
    {
        return isset(self::$vars[$key]) ? self::$vars[$key] : $default;
    }

    /**
     * @param $name
     * @param null $options
     * @return Subscriber | void
     */
    public function subscribe($name, $options = null)
    {
        if (!is_null($options))
            $this->setSubscriber($name, $options);
        return $this->getSubscriber($name);
    }

    /**
     * @return array
     */
    public function getSubscribers()
    {
        return $this->subscribers;
    }

    public function singleton($name, $Class)
    {
        if (!isset(self::$singleton[$name]))
            self::$singleton[$name] = new $Class();
    }

    protected function getContainer($name)
    {
        if (isset(self::$singleton[$name]))
            return self::$singleton[$name];
        if (isset($this->containers[$name]))
            return clone $this->containers[$name];
        return null;
    }

    protected function setContainer($name, $Class)
    {
        $this->containers[$name] = new $Class();
    }

    protected function getProvider($name)
    {
        return $this->providers[$name];
    }

    protected function setProvider($name, $Class)
    {
        $this->providers[$name] = new $Class(self::getInstance());
    }

    protected function getSubscriber($name)
    {
        return $this->subscribers[$name];
    }

    protected function setSubscriber($name, $options)
    {
        $this->subscribers[$name] = new Subscriber($name, $options);
    }

}