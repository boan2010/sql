<?php

namespace mindplay\sql\pdo;

use InvalidArgumentException;
use mindplay\sql\framework\PreparedStatement;
use mindplay\sql\framework\SQLException;
use mindplay\sql\framework\Status;
use PDO;
use PDOStatement;

/**
 * This class implements a Prepared Statement adapter for PDO Statements.
 */
class PreparedPDOStatement implements PreparedStatement
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var PDOStatement
     */
    private $handle;

    /**
     * @var array
     */
    private $params;

    /**
     * @var bool
     */
    private $executed = false;

    /**
     * @var string[]
     */
    private $sequence_names;

    /**
     * @param PDO          $pdo
     * @param PDOStatement $handle
     * @param string[]     $sequence_names map where Column name => sequence name
     */
    public function __construct(PDO $pdo, PDOStatement $handle, array $sequence_names)
    {
        $this->pdo = $pdo;
        $this->handle = $handle;
        $this->sequence_names = $sequence_names;
    }

    /**
     * @inheritdoc
     */
    public function bind($name, $value)
    {
        static $PDO_TYPE = [
            'integer' => PDO::PARAM_INT,
            'double'  => PDO::PARAM_STR, // bind as string, since there's no float type in PDO
            'string'  => PDO::PARAM_STR,
            'boolean' => PDO::PARAM_BOOL,
            'NULL'    => PDO::PARAM_NULL,
        ];

        $value_type = gettype($value);

        if (isset($PDO_TYPE[$value_type])) {
            $this->handle->bindValue($name, $value, $PDO_TYPE[$value_type]);

            $this->params[$name] = $value;
        } else {
            throw new InvalidArgumentException("unexpected value type: {$value_type}");
        }
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        if ($this->handle->execute() === false) {
            $error = $this->handle->errorInfo();
            
            throw new SQLException($this->handle->queryString, $this->params, "{$error[0]}: {$error[2]}", $error[1]);
        }

        $this->executed = true;

        $keys = [];

        foreach ($this->sequence_names as $column_name => $sequence_name) {
            // NOTE: spooky action in the distance - the internal state of the PDO connection
            // object gets internally updated as a side-effect of calling PDOStatement::execute().
            //
            // PDO is fucking lovely.

            $keys[$column_name] = $this->pdo->lastInsertId($sequence_name);
        }
        
        return new Status($this->handle->rowCount(), $keys);
    }
    
    /**
     * @inheritdoc
     */
    public function fetch()
    {
        if (! $this->executed) {
            $this->execute();
        }

        $result = $this->handle->fetch(PDO::FETCH_ASSOC);
        
        if ($result === false) {
            return null;
        }
        
        return $result;
    }
}
