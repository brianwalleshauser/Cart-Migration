<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/23/2015
 * Time: 8:24 AM
 */

namespace lib\Model;


class Where
{
    protected $table;
    protected $column;
    protected $operator;
    protected $value;

    public function __construct($table,$column,$operator,$value)
    {
        $this->table = $table;
        $this->column = $column;
        $this->operator = $operator;
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @return mixed
     */
    public function getOperator()
    {
        return $this->operator;
    }

    public function getTable()
    {
        return $this->table;
    }


}