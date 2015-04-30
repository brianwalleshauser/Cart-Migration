<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/23/2015
 * Time: 8:59 AM
 */

namespace lib\Model;


class Join
{
    protected $type;
    protected $origTable;
    protected $newTable;

       public function __construct($type,$origTable,$newTable,$on)
    {
        $this->type = $type;
        $this->origTable = $origTable;
        $this->newTable = $newTable;
        $this->on = $on;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getOn()
    {
        return $this->on;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getOrigTable()
    {
        return $this->origTable;
    }

    /**
     * @return mixed
     */
    public function getNewTable()
    {
        return $this->newTable;
    }
    protected $on;

}