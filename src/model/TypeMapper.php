<?php

namespace mindplay\sql\model;

use mindplay\sql\framework\Mapper;
use OutOfBoundsException;
use UnexpectedValueException;

class TypeMapper implements Mapper
{
    /**
     * @var Type[] map where return variable name maps to Type
     */
    private $types;

    /**
     * @param $types Type[] map where return variable name maps to Type
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * @param array $record_set
     *
     * @return array
     */
    public function map(array $record_set)
    {
        foreach ($record_set as $index => &$record) {
            if (! is_array($record)) {
                throw new UnexpectedValueException("unexpected record type: " . gettype($record));
            }

            foreach ($this->types as $name => $type) {
                if (! array_key_exists($name, $record)) {
                    throw new OutOfBoundsException("undefined record field: {$name}");
                }

                $record[$name] = $type->convertToPHP($record[$name]);
            }
        }

        return $record_set;
    }
}