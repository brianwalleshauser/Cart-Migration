<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/31/2015
 * Time: 5:44 PM
 */

namespace app\Magento\Model;

class GroupedProduct extends \lib\Model\Data
{
    public $type_id;
    public $created_at;
    public $sku;
    public $name;
    public $status;
    public $description;
    public $short_description;
    public $manufacturer;
    public $associatedProducts;


    function __construct()
    {
        $this->type_id              = 'grouped';
        $this->created_at           = strtotime('now');
        $this->sku                  = '-GROUPED';
        $this->name                 = '';
        $this->status               = 1;
        $this->description          = '';
        $this->short_description    = '';
        $this->manufacturer         = '';
        $this->associatedProducts   = array();

        return $this;
    }


    public function Hydrate($product)
    {
        $productSku = trim($product->get('sku'));

        $this->sku                  = (!empty($productSku) ? $product->get('sku') : $product->get('productid')) . $this->sku;
        $this->name                 = $product->get('name');
        $this->status               = 1;
        $this->tax_class_id         = 4;
        $this->description          = $product->get('description');
        $this->short_description    = $product->get('name');
        $this->manufacturer         = '';

        $variants = $product->get('variants')->get('objects');

        foreach($variants as $variant)
        {
            array_push($this->associatedProducts,(new SimpleProduct())->Hydrate($product,$variant));
        }


        return $this;
    }
}