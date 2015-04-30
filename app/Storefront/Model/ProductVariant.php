<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/23/2015
 * Time: 10:51 AM
 */

namespace app\Storefront\Model;


class ProductVariant extends \lib\Model\Data
{
    //region vars
    protected $variantId;
    protected $isDefault;
    protected $name;
    protected $skuSuffix;
    protected $price;
    protected $weight;
    protected $colors;
    protected $sizes;
    protected $parentId;
    protected $colorSkuModifiers;
    protected $sizeSkuModifiers;
    protected $parentSku;
    protected $sfFields;
    //endregion


    function __construct()
    {
        $this->variantId = 0;
        $this->isDefault = false;
        $this->name = '';
        $this->skuSuffix = '';
        $this->price = 0.00;
        $this->weight = 0.00;
        $this->colors = '';
        $this->sizes = '';
        $this->parentId = 0;
        $this->colorSkuModifiers = '';
        $this->sizeSkuModifiers = '';
        $this->parentSku = '';
        $this->inventory = 0;
        $this->sfFields = array(
                            'variantId',
                            'isDefault',
                            'name',
                            'skuSuffix',
                            'price',
                            'weight',
                            'colors',
                            'sizes',
                            'colorskumodifiers',
                            'sizeskumodifiers',
                            'inventory'
                        );

        return $this;
    }


    public function Hydrate($data)
    {
        foreach($data as $key => $value)
        {
            $this->$key = $value;
        }

        $this->parentId = $data['productid'];
        $this->parentSku = $data['sku'];

                //assuming sf images are named by productid
        //\app\Storefront\Helper\ImageHelper::GetImageByCurl($this->parentId . '_1_' . $this->name,$this->parentSku . $this->skuSuffix);



        return $this;
    }
}