<?php

namespace Lib\Driver\MySQL;

use PDO;
use Exception;
use Lib\Core\Driver;
use Lib\Driver\Columns;

class MySQL extends Driver
{
    /**
     * @var PDO
     */
    protected $pdo;

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
        $this->pdo = $this->getPdo();
        $this->column = app()->make('column');
        $this->sql = app()->make('sql');
    }

    private function getPdo()
    {
        try {
            $dsn = "mysql:host={$this->connection['host']};dbname={$this->connection['database']};charset=utf8";
            return new PDO($dsn, $this->connection['username'], $this->connection['password']);
        } catch (Exception $e) {
            return null;
        }
    }

    public function reconnect()
    {
        $this->pdo = $this->getPdo();
        return $this;
    }

    public function disconnect()
    {
        unset($this->pdo);
    }

    public function isConnect()
    {
        return !is_null($this->pdo);
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
        return $this->pdo->exec($sql) > 0 ? null : $sql;
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
            return $this->pdo->exec($sql) > 0 ? null : $sql;
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
        return $this->pdo->exec($sql) > 0 ? null : $sql;
    }

}