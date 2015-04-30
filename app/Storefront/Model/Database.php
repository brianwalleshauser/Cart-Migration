<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/23/2015
 * Time: 7:19 AM
 */

namespace app\Storefront\Model;

use lib\Model\Where;

class Database implements \lib\Model\IDatabase
{
    protected $selectColumns;
    protected $table;
    protected $where;
    protected $orderBy;
    protected $join;
    protected $connection;
    protected $ntext;
    protected $limit;

    function __construct()
    {
        $this->selectColumns = array();
        $this->table = '';
        $this->where = array();
        $this->orderBy = array();
        $this->join = array();
        $this->ntext = array();
        $this->limit = 0;
    }



    public function Connect($server,$username,$password)
    {
        $this->connection = mssql_connect($server,$username,$password);

        return $this;
    }


    public function Select($columns,$table,$as = '')
    {
        if(!is_array($columns))
        {
            $this->selectColumns[$table . '.' . $columns] = (!empty($as) ? $as : $columns);
        }
        else
        {
            foreach($columns as $column)
            {
                $this->selectColumns[$table . '.' . $column] = $column;
            }
        }

        return $this;
    }


    public function From($table)
    {
        $this->table = $table;

        $query = mssql_query('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = \'' . $table . '\' AND data_type = \'ntext\'',$this->connection);


        if(mssql_num_rows($query))
        {
            $results = array();

            while($row = mssql_fetch_array($query,MSSQL_ASSOC))
            {
                array_push($this->ntext,strtolower($row['COLUMN_NAME']));
            }
        }

        return $this;
    }


    public function Where($table,$column,$operator,$value)
    {
        if(empty($this->where))
        {
            $this->where = array();
        }

        array_push($this->where,new \lib\Model\Where($table,$column,$operator,$value));

        return $this;
    }


    public function OrderBy($table,$column,$order = NULL)
    {
        if(empty($this->orderBy))
        {
            $this->orderBy = array();
        }

        array_push($this->orderBy,new \lib\Model\Orderby($table,$column,$order));

        return $this;
    }


    public function Join($type,$origTable,$newTable,$on)
    {
        if(empty($this->join))
        {
            $this->join = array();
        }

        array_push($this->join,new \lib\Model\Join($type,$origTable,$newTable,$on));

        return $this;
    }


    public function Limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }



    public function ToString()
    {
        return $this->ParseStatement();
    }


    private function ParseStatement()
    {
        return $this->ParseSelect()
        . $this->ParseFrom()
        . $this->ParseJoin()
        . $this->ParseWhere()
        . $this->ParseOrderBy();
    }


    private function ParseSelect()
    {
        $statements = 'SELECT ';

        if($this->limit > 0)
        {
            $statements .= 'TOP ' . $this->limit . ' ';
        }

        foreach($this->selectColumns as $key => $value)
        {
            if($value === reset($this->selectColumns))
            {
                $statements .= $this->CastNtext($key) . ' AS ' . $value;
            }
            else
            {
                $statements .= ', ' . $this->CastNtext($key) . ' AS ' . $value;
            }
        }

        return $statements;
    }


    private function ParseFrom()
    {
        return ' FROM ' . $this->table;
    }


    private function ParseJoin()
    {
        $joinStatement = '';

        if(!empty($this->join))
        {
            foreach($this->join as $join)
            {
                $joinStatement .= ' ';
                $joinStatement .= strtoupper($join->getType());
                $joinStatement .= ' JOIN ';
                $joinStatement .= $join->getNewTable();
                $joinStatement .= ' ON ';
                $joinStatement .= $join->getOrigTable();
                $joinStatement .= '.';
                $joinStatement .= $join->getOn();
                $joinStatement .= ' = ';
                $joinStatement .= $join->getNewTable();
                $joinStatement .= '.';
                $joinStatement .= $join->getOn();
            }
        }

        return $joinStatement;
    }

    private function ParseWhere()
    {
        $whereStatement = '';

        if(!empty($this->where))
        {
            foreach($this->where as $where)
            {
                $whereStatement .= ' WHERE ';
                $whereStatement .= $where->getTable();
                $whereStatement .= '.';
                $whereStatement .= $where->getColumn();
                $whereStatement .= ' ';
                $whereStatement .= $where->getOperator();
                $whereStatement .= ' ';
                $whereStatement .= $where->getValue();
            }
        }

        return $whereStatement;
    }

    private function ParseOrderBy()
    {
        $orderByStatement = '';

        if(!empty($this->orderBy))
        {
            foreach($this->orderBy as $orderBy)
            {
                $orderByStatement .= ' ORDER BY ';
                $orderByStatement .= $orderBy->getTable();
                $orderByStatement .= '.';
                $orderByStatement .= $orderBy->getColumn();
                if($orderBy->getOrder() !== NULL)
                {
                    $orderByStatement .= ' ';
                    $orderByStatement .= $orderBy->getOrder();
                }
            }
        }

        return $orderByStatement;
    }


    private function CastNtext($column)
    {
        $result = $column;

        if(in_array(strtolower(str_replace($this->table . '.','',$column)),$this->ntext))
        {
            $result = 'CAST(' . $column . ' AS TEXT)';
        }

        return $result;
    }


    public function UseDatabase($database)
    {
        mssql_select_db($database,$this->connection);

        return $this;
    }


    private function ParseLimit()
    {
        return ' TOP ' . $this->limit;
    }


    public function Execute($statement)
    {
        $query = mssql_query($statement,$this->connection);
        $results = false;

        if(mssql_num_rows($query))
        {
            $results = array();

            while($row = mssql_fetch_array($query,MSSQL_ASSOC))
            {
                array_push($results,$row);
            }
        }
        else
        {
            \lib\Helper\Log::Log(array(
                mssql_get_last_message(),
                $statement
            ));
        }

        mssql_free_result($query);

        return $results;
    }
}