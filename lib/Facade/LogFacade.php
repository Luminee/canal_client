<?php

namespace Lib\Facade;

use Lib\Core\Log;

class LogFacade
{
    /**
     * @var Log
     */
    protected static $logger;

    protected static function getLogger()
    {
        if (!self::$logger)
            self::$logger = app()->make('log');
        return self::$logger;
    }

    /**
     * @param $module
     * @param null $message
     */
    public static function debug($module, $message = null)
    {
        self::getLogger()->debug($module, $message);
    }

    /**
     * @param $module
     * @param null $message
     */
    public static function info($module, $message = null)
    {
        self::getLogger()->info($module, $message);
    }

    /**
     * @param $module
     * @param null $message
     */
    public static function notice($module, $message = null)
    {
        self::getLogger()->notice($module, $message);
    }

    /**
     * @param $module
     * @param null $message
     */
    public static function warning($module, $message = null)
    {
        self::getLogger()->warning($module, $message);
    }

    /**
     * @param $module
     * @param null $message
     */
    public static function error($module, $message = null)
    {
        self::getLogger()->error($module, $message);
    }

    /**
     * @param $module
     * @param null $message
     */
    public static function alert($module, $message = null)
    {
        self::getLogger()->alert($module, $message);
    }

}