<?php

namespace Lib\Core;

class Config
{
    protected $config = [];

    public function __construct()
    {
        if (empty($this->config)) $this->initConfig();
    }

    public function get($key, $default = null)
    {
        $arr = $this->config;
        foreach (explode('.', $key) as $k) {
            if (!isset($arr[$k])) return $default;
            $arr = $arr[$k];
        }
        return $arr;
    }

    public function set($array)
    {
        if (empty($array)) return;
        foreach ($array as $key => $value) {
            $arr = &$this->config;
            foreach (explode('.', $key) as $k) {
                if (!isset($arr[$k])) return;
                $arr = &$arr[$k];
            }
            $arr = $value;
            unset($arr);
        }
    }

    protected function initConfig()
    {
        $dir = base_path() . '/config';
        foreach (scandir($dir) as $file) {
            if (in_array($file, ['.', '..'])) continue;
            $this->pushConf($file, $dir);
        }
    }

    protected function pushConf($file, $dir)
    {
        $key = str_replace('.php', '', $file);
        $this->config[$key] = include $dir . '/' . $file;
    }
}