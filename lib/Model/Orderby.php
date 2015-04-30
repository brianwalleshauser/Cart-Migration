<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/23/2015
 * Time: 8:24 AM
 */

namespace lib\Model;


class Orderby
{
    protected $table;
    protected $column;
    protected $order;

    public function __construct($table,$column,$order = NULL)
    {
        $this->table = $table;
        $this->column = $column;
        $this->order = $order;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return mixed
     */
    public function getColumn()
    {
        return $this->column;
    }

    public function getTable()
    {
        return $this->table;
    }


}