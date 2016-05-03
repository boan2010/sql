<?php

namespace mindplay\sql\model;

use mindplay\sql\framework\Driver;

/**
 * This class represents a Column belonging to a Table.
 */
class Column
{
    /**
     * @var Table
     */
    private $table;

    /**
     * @var Driver
     */
    private $driver;

    /**
     * @var Type
     */
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var bool
     */
    private $required;

    /**
     * @var mixed
     */
    private $default;

    /**
     * @var bool
     */
    private $auto;

    /**
     * @var string|null
     */
    private $sequence_name;

    /**
     * @param Driver      $driver
     * @param Table       $table parent Table instance
     * @param string      $name
     * @param Type        $type
     * @param string|null $alias
     * @param bool        $required
     * @param mixed       $default
     * @param bool        $auto
     * @param string|null $sequence_name
     */
    public function __construct(
        Driver $driver,
        Table $table,
        $name,
        Type $type,
        $alias,
        $required,
        $default,
        $auto,
        $sequence_name
    )
    {
        $this->table = $table;
        $this->driver = $driver;
        $this->type = $type;
        $this->name = $name;
        $this->alias = $alias;
        $this->required = $required;
        $this->default = $default;
        $this->auto = $auto;
        $this->sequence_name = $sequence_name;
    }

    /**
     * @return Table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return bool
     */
    public function isAuto()
    {
        return $this->auto;
    }

    /**
     * @return string|null
     */
    public function getSequenceName()
    {
        return $this->sequence_name;
    }

    /**
     * @ignore
     *
     * @return string
     */
    public function __toString()
    {
        return $this->table->__toString() . '.' . $this->driver->quoteName($this->name);
    }
}
