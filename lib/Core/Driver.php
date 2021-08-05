<?php

namespace Lib\Core;

abstract class Driver
{
    protected $connection;

    protected $rule = [];

    public function init($conn, $rule)
    {
        $this->connection = $conn;
        $this->rule = $rule;

        $this->initSelf();
        return $this;
    }

    abstract protected function initSelf();

    abstract public function setRule($index);

    /**
     * @return $this
     */
    abstract public function reconnect();

    /**
     * @return void
     */
    abstract public function disconnect();

    /**
     * @return bool
     */
    abstract public function isConnect();
}