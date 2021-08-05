<?php

/**
 * Application
 */

if (!function_exists('app')) {
    /**
     * @return Lib\Core\Application|null
     */
    function app()
    {
        return Lib\Core\Application::getInstance();
    }
}

if (!function_exists('env')) {
    /**
     * @param $key
     * @param null $default
     * @return array|bool|string|null
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        return $value;
    }
}

if (!function_exists('base_path')) {
    /**
     * @return string
     */
    function base_path()
    {
        return realpath(__DIR__ . '/../');
    }
}

if (!function_exists('storage_path')) {
    /**
     * @return string
     */
    function storage_path()
    {
        return realpath(__DIR__ . '/../storage/');
    }
}

/**
 * Config
 */

if (!function_exists('config')) {
    /**
     * @param $key
     * @param null $default
     * @return mixed | void
     */
    function config($key, $default = null)
    {
        /** @var Lib\Core\Config $config */
        $config = app()->make('config');
        if (!is_array($key)) {
            return $config->get($key, $default);
        }
        $config->set($key);
    }
}
