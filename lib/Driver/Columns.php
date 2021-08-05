<?php

namespace Lib\Driver;

class Columns
{
    protected $id = null;

    protected $columns = [];

    protected function init()
    {
        $this->id = null;
        $this->columns = [];
    }

    /**
     * @param $columns
     * @return $this
     */
    public function parseColumns($columns)
    {
        $this->init();
        foreach ($columns as $column) {
            if ($column->getName() == 'id') $this->id = $column->getValue();
            if ($column->getUpdated())
                $this->columns[$column->getName()] = $column->getIsNull() ? null : $column->getValue();
        }
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getColumns()
    {
        return $this->columns;
    }

}