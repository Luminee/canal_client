<?php

namespace Lib\Provider;

class SubscriberProvider extends ServiceProvider
{
    protected $baseRule = [];

    protected $baseDir = '';

    public function register()
    {
        $this->baseDir = base_path() . '/subscribers';
        $this->getRules();
        $this->getSubscribers();
    }

    protected function getSubscribers()
    {
        foreach (explode(',', config('app.subscribers')) as $_dir) {
            foreach (scandir($this->baseDir . '/' . $_dir) as $file) {
                if (in_array($file, ['.', '..', '.gitignore'])) continue;
                if (strstr($file, 'example.php')) continue;
                $this->pushConf($file, $_dir);
            }
        }
    }

    protected function getRules()
    {
        $rules = config('rules');
        $this->baseRule['_base'] = array_shift($rules);
        foreach ($rules as $project => $rule) {
            $this->baseRule[$project] = [];
            $this->mergeRules($project, $rule);
        }
    }

    protected function mergeRules($project, $rule)
    {
        if (!empty($rule['extend']))
            $this->handleExtend($project, $rule['extend']);
        if (!empty($rule['custom']))
            $this->baseRule[$project] = array_merge($this->baseRule[$project], $rule['custom']);
    }

    protected function handleExtend($project, $extend)
    {
        foreach ($extend as $index) {
            $this->baseRule[$project] = array_merge($this->baseRule[$project], $this->baseRule[$index]);
        }
    }

    protected function pushConf($file, $dir)
    {
        $key = str_replace('/', '_', $dir) . '_' . str_replace('.php', '', $file);
        $value = include $this->baseDir . '/' . $dir . '/' . $file;
        $this->app->subscribe($key, array_merge(['baseRule' => $this->baseRule], $value));
    }
}