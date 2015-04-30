<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 4/1/2015
 * Time: 9:06 AM
 */

namespace app\Magento\Model;


class Magento extends \lib\Model\Data
{
    protected $simpleProducts;
    protected $groupedProducts;


    function __construct()
    {
        $this->simpleProducts   = array();
        $this->groupedProducts  = array();

        return $this;
    }


    public function Hydrate($productFromOtherCart)
    {



        if($productFromOtherCart->get('variants')->getNumObjects() > 1)
        {
            array_push($this->groupedProducts,(new GroupedProduct())->Hydrate($productFromOtherCart));
        }
        else
        {
            $variants = $productFromOtherCart->get('variants')->get('objects');

            foreach($variants as $variant)
            {
                array_push($this->simpleProducts,(new SimpleProduct())->Hydrate($productFromOtherCart,$variant));
            }
        }




        return $this;
    }
}