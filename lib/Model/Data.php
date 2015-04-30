<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/31/2015
 * Time: 4:11 PM
 */

namespace lib\Model;


abstract class Data
{
    public function get($member)
    {
        if(isset($this->$member))
        {
            return $this->$member;
        }

        $vars = get_object_vars($this);

        foreach($vars as $key => $var)
        {
            if (strcasecmp($member, $key) == 0)
            {
                return $this->$key;
            }
        }
    }


    public function set($member,$value)
    {
        $this->$member = $value;

        return $this;
    }


    public function has($member)
    {
        if(is_array($this->$member))
        {
            return (bool)count($this->$member);
        }

        if(is_a($this->$member, '\lib\Model\Collection'))
        {
            return (bool)count($this->$member->get('objects'));
        }

    }
}