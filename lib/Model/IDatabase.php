<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/23/2015
 * Time: 7:13 AM
 */

namespace lib\Model;


interface IDatabase
{
    public function Connect($server,$username,$password);
    public function Select($columns,$table);
    public function From($table);
    public function Where($table,$column,$operator,$value);
    public function OrderBy($table,$column,$order);
}