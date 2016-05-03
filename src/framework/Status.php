<?php

namespace mindplay\sql\framework;

use mindplay\sql\model\Column;
use UnexpectedValueException;

/**
 * This class represents the status of executing a non-returning query.
 */
class Status
{
    /**
     * @param int   $rows_affected
     * @param array $keys
     */
    public function __construct($rows_affected, array $keys)
    {
        $this->rows_affected = $rows_affected;
        $this->keys = $keys;
    }

    /**
     * @return int number of rows affected by an INSERT, UPDATE or DELETE
     */
    public function getRowsAffected()
    {
        return $this->rows_affected;
    }

    /**
     * @param Column|string $column Column instance (or Column name)
     *                              
     * @return mixed auto-sequenced key
     */
    public function getKey($column)
    {
        $name = $column instanceof Column
            ? $column->getName()
            : (string) $column;
        
        if (!isset($this->keys[$name])) {
            throw new UnexpectedValueException("undefined key: {$name}");
        }
        
        return $this->keys[$name];
    }
}
