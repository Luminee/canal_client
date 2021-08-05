<?php

namespace Lib\Core;

use Com\Alibaba\Otter\Canal\Protocol\EventType;
use Com\Alibaba\Otter\Canal\Protocol\RowChange;

class Subscriber
{
    /**
     * @var string $name
     */
    protected $name;
    /**
     * @var Driver $driver
     */
    protected $driver;

    /**
     * @var Log $log
     */
    protected $log;

    /**
     * @var array
     */
    protected $connection = [];

    /**
     * @var array
     */
    protected $rule = [];

    /**
     * @var array
     */
    protected $indexes = [];

    /**
     * @var array
     */
    protected $rails = [
        EventType::INSERT => ['insert', 'getAfterColumns'],
        EventType::UPDATE => ['update', 'getAfterColumns'],
        EventType::DELETE => ['delete', 'getBeforeColumns']
    ];

    public function __construct($name, $options)
    {
        $this->name = $name;
        $this->log = new Log();
        $this->connection = $options['connection'];
        $this->setRule($options['baseRule'], $options['rule']);
        $this->indexes = array_keys($this->rule);
        $this->initDriver($options['driver']);
    }

    /**
     * @return array
     */
    public function showRule()
    {
        return $this->rule;
    }

    protected function setRule($baseRule, $rule)
    {
        if ($rule['extend'] !== false) {
            foreach ($rule['extend'] as $project) {
                $this->rule = array_merge($this->rule, $baseRule[$project]);
            }
        }
        foreach ($this->rule as $index => $_rule) {
            if (isset($rule['ignore']) && in_array($index, $rule['ignore']))
                unset($this->rule[$index]);
        }
        $this->rule = array_merge($this->rule, $rule['custom']);
        $this->rule = $this->mergeSchema($this->rule, $rule['schema']);
    }

    protected function mergeSchema($rules, $schema)
    {
        $_rules = [];
        foreach ($rules as $index => $rule) {
            $_rules[$schema . '.' . $index] = $rule;
        }
        return $_rules;
    }

    protected function initDriver($driver)
    {
        $this->driver = app()->make($driver);
        $this->driver->init($this->connection, $this->rule);
    }

    /**
     * @param $index
     * @param RowChange $rowChange
     * @return void
     */
    public function handle($index, $rowChange)
    {
        if (!in_array($index, $this->indexes)) return;
        $this->checkReconnect();
        $this->driver->setRule($index);
        list($method, $columnMethod) = $this->rails[$rowChange->getEventType()];
        foreach ($rowChange->getRowDatas() as $rowData) {
            $status = $this->driver->$method($rowData->$columnMethod());
            if (!is_null($status)) $this->log->error($this->name . ':' . $status);
        }
    }

    protected function checkReconnect()
    {
        if (!$this->driver->isConnect() || app()->getVar('reconnect') === true) {
            $this->driver->reconnect();
            app()->setVar('reconnect', false);
        }
    }

}