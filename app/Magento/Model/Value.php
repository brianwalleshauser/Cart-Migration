<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/31/2015
 * Time: 6:35 PM
 */

namespace app\Magento\Model;


class Value extends \lib\Model\Data
{
    public $title;
    public $price;
    public $price_type;
    public $sku;
    public $sort_order;


    function __construct()
    {
        $this->title        = '';
        $this->price        = 0.00;
        $this->price_type   = 'fixed';
        $this->sku          = '';
        $this->sort_order   = 0;

        return $this;
    }


    public function Hydrate($value)
    {
        $this->title        = $value->get('name');
        $this->price        = $value->get('priceDelta');
        $this->price_type   = 'fixed';
        $this->sku          = $value->get('inventoryVariantId');
        $this->sort_order   = $value->get('displayOrder');

        return $this;
    }
}