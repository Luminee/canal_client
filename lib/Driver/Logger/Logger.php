<?php

namespace Lib\Driver\Logger;

use Log;
use Lib\Core\Driver;
use Lib\Driver\Columns;
use Lib\Driver\MySQL\SQL;

class Logger extends Driver
{
    /**
     * @var SQL
     */
    protected $sql;

    /**
     * @var Columns
     */
    protected $column;

    protected function initSelf()
    {
        $this->column = app()->make('column');
        $this->sql = app()->make('sql');
    }


    public function reconnect()
    {
        return $this;
    }

    public function disconnect()
    {
    }

    public function isConnect()
    {
        return true;
    }

    /**
     * @param $index
     */
    public function setRule($index)
    {
        $this->sql->setRule($this->rule[$index]);
    }

    /**
     * @param $columns
     * @return string | null
     */
    public function insert($columns)
    {
        $rows = $this->column->parseColumns($columns)->getColumns();
        $sql = $this->sql->buildInsert($rows);
        Log::info($sql);
        return null;
    }

    /**
     * @param $columns
     * @return string | null
     */
    public function update($columns)
    {
        $columns = $this->column->parseColumns($columns);
        $sql = $this->sql->buildUpdate($columns->getColumns(), $columns->getId());
        if (!empty($sql))
            Log::info($sql);
        return null;
    }

    /**
     * @param $columns
     * @return string | null
     */
    public function delete($columns)
    {
        $id = $this->column->parseColumns($columns)->getId();
        $sql = $this->sql->buildDelete($id);
        Log::info($sql);
        return null;
    }

}