<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/31/2015
 * Time: 6:32 PM
 */

namespace app\Magento\Model;


class Option extends \lib\Model\Data
{
    public $title;
    public $type;
    public $is_require;
    public $sort_order;
    public $values;

    function __construct()
    {
        $this->title        = '';
        $this->type         = '';
        $this->is_require   = 0;
        $this->sort_order   = 0;
        $this->values       = array();

        return $this;
    }


    public function Hydrate($option)
    {
        $this->title        = $option->get('name');
        $this->type         = \app\Magento\Helper\Option::ConvertOptionType($option->get('KitGroupType'));
        $this->is_require   = $option->get('isRequired');
        $this->sort_order   = $option->get('displayOrder');

        foreach($option->get('kitItems')->get('objects') as $kititem)
        {
            array_push($this->values,(array)(new Value())->Hydrate($kititem));
        }

        return $this;
    }
}