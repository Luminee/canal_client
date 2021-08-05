<?php

namespace Lib\Driver\MySQL;

class SQL
{
    protected $rule;

    public function setRule($rule)
    {
        $this->rule = $rule;
        return $this;
    }

    /**
     * @param $data
     * @return string
     */
    public function buildInsert($data)
    {
        $col = $val = '';
        $method = 'insert' . $this->snakeToCamel($this->rule['method']);
        $this->$method($data, $col, $val);
        return "INSERT INTO `{$this->rule['table']}` " . '(' . rtrim($col, ',') . ') VALUES (' . rtrim($val, ',') . ')';
    }

    protected function insertCopyFully($data, &$col, &$val)
    {
        foreach ($data as $column => $value) {
            if (is_null($value)) continue;
            $col .= "`$column`,";
            $value = str_replace(['\'', '"'], ['\\\'', '\"'], $value);
            $val .= "'$value',";
        }
    }

    protected function insertCopyPartly($data, &$col, &$val)
    {
        $ignores = $this->rule['ignore'];
        $rows = [];
        foreach ($data as $column => $value) {
            $rows[$column] = $value;
            if (is_null($value)) continue;
            if (in_array($column, $ignores)) continue;
            $col .= "`$column`,";
            $value = str_replace(['\'', '"'], ['\\\'', '\"'], $value);
            $val .= "'$value',";
        }
        if (isset($this->rule['append'])) {
            foreach ($this->rule['append'] as $column => $value) {
                $col .= "`$column`,";
                if ($value instanceof \Closure) $value = $value($rows);
                $value = str_replace(['\'', '"'], ['\\\'', '\"'], $value);
                $val .= "'$value',";
            }
        }
    }

    /**
     * @param $data
     * @param $id
     * @return string
     */
    public function buildUpdate($data, $id)
    {
        $set = '';
        $method = 'update' . $this->snakeToCamel($this->rule['method']);
        $this->$method($data, $set);
        return empty($set) ? '' : "UPDATE `{$this->rule['table']}` SET " . rtrim($set, ',') . " WHERE id = $id";
    }

    protected function updateCopyFully($data, &$set)
    {
        foreach ($data as $column => $value) {
            $set .= $this->updateSetColumn($column, $value);
        }
    }

    protected function updateCopyPartly($data, &$set)
    {
        $ignores = $this->rule['ignore'];
        foreach ($data as $column => $value) {
            if (in_array($column, $ignores)) continue;
            $set .= $this->updateSetColumn($column, $value);
        }
    }

    protected function updateSetColumn($column, $value)
    {
        if (is_null($value)) return "`$column` = null,";
        $value = str_replace(['\'', '"'], ['\\\'', '\"'], $value);
        return "`$column` = '$value',";
    }

    /**
     * @param $id
     * @return string
     */
    public function buildDelete($id)
    {
        $table = $this->rule['table'];
        return "DELETE FROM `{$table}` WHERE id = $id";
    }

    protected function snakeToCamel($string)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }
}