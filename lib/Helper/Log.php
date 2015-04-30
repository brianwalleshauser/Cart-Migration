<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/24/2015
 * Time: 12:04 PM
 */

namespace lib\Helper;

class Log
{
    public static function Log($data)
    {
        if(DEBUG)
        {
            if(!is_array($data))
            {
                echo $data;
            }
            else
            {
                foreach($data as $datum)
                {
                    echo $datum . "<br />";
                }
            }
        }

        die();
    }
}