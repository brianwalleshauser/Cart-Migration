<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/31/2015
 * Time: 5:44 PM
 */

namespace app\Magento\Model;

class SimpleProduct extends \lib\Model\Data
{
    public $type_id;
    public $created_at;
    public $sku;
    public $name;
    public $weight;
    public $status;
    public $visibility;
    public $tax_class_id;
    public $price;
    public $description;
    public $short_description;
    public $manufacturer;
    public $qty;
    public $options;

    function __construct()
    {
        $this->type_id              = 'simple';
        $this->created_at           = strtotime('now');
        $this->sku                  = '';
        $this->name                 = '';
        $this->weight               = 0.00;
        $this->status               = 1;
        $this->tax_class_id         = 4;
        $this->price                = 0.00;
        $this->description          = '';
        $this->short_description    = '';
        $this->manufacturer         = '';
        $this->qty                  = 0;
        $this->options              = array();

        return $this;
    }


    public function Hydrate($product,$variant)
    {
        $variantName = trim($variant->get('name'));
        $variantSku = trim($variant->get('skuSuffix'));
        $productSku = trim($product->get('sku'));

        $this->sku                  = (!empty($productSku) ? $product->get('sku') . '-' .  (!empty($variantSku) ? $product->get('sku') : $variant->get('variantId')) : strtolower(preg_replace('/[^a-z0-9]/i', '-',$variant->get('name'))));
        if($product->get('variants')->getNumObjects() > 1)
        {
            $this->name             = $variant->get('name');
        }
        else
        {
            $this->name             = $product->get('name') . (!empty($variantName) ? ' - ' . $variant->get('name') : '');
        }
        $this->weight               = $variant->get('weight');
        $this->qty                  = $variant->get('inventory');
        $this->status               = 1;
        $this->tax_class_id         = 4;
        $this->price                = $variant->get('price');
        $this->description          = $product->get('description');
        $this->short_description    = $product->get('name') . (!empty($variantName) ? ' - ' . $variant->get('name') : '');
        $this->manufacturer         = '';

        if($product->get('isAKit'))
        {
            foreach($product->get('kit')->get('objects') as $kitGroup)
            {
                array_push($this->options,(array)(new Option())->Hydrate($kitGroup));
            }
        }

        return $this;
    }
}
