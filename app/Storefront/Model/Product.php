<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/19/2015
 * Time: 5:29 PM
 */

namespace app\Storefront\Model;

class Product extends \lib\Model\Data
{
    //region vars
    protected $name;
    protected $sku;
    protected $description;
    protected $seTitle;
    protected $seKeywords;
    protected $seDescription;
    protected $productId;
    protected $seName;
    protected $imagePaths;
    protected $isAKit;
    protected $hasMultipleVariants;
    protected $defaultVariantId;
    protected $variants;
    protected $kit;
    protected $sfFields;
    //endregion


    function __construct()
    {
        $this->name                 = '';
        $this->sku                  = '';
        $this->description          = '';
        $this->seTitle              = '';
        $this->seKeywords           = '';
        $this->seDescription        = '';
        $this->productId            = 0;
        $this->seName               = '';
        $this->imagePaths           = array();
        $this->isAKit               = 0;
        $this->hasMultipleVariants  = false;
        $this->defaultVariantId     = 0;
        $this->variants             = new \lib\Model\Collection();
        $this->kit                  = new \lib\Model\Collection();
        $this->sfFields             = array(
                                        'name',
                                        'sku',
                                        'description',
                                        'seTitle',
                                        'seKeywords',
                                        'seDescription',
                                        'productId',
                                        'seName',
                                        'isAKit'
                                    );

        return $this;
    }


    public function Hydrate($data)
    {

        //region ProductData
        foreach($data as $key => $value)
        {
            $this->$key = $value;
        }
        //endregion

        //region ProductImages
                //assuming sf images are named by productid
        \app\Storefront\Helper\ImageHelper::GetImageByCurl($this->productId,$this->sku);
        //endregion

        //region ProductVariants
        $db = new Database();
        $db->Connect(SFSERVER,SFUSER,SFPASS);
        $select = $db->Select((new ProductVariant())->get('sfFields'),'ProductVariant')
            ->Select('productid','ProductVariant')
            ->Select('sku','Product')
            ->UseDatabase(SFDATABASE)
            ->From('ProductVariant')
            ->Join('inner','ProductVariant','Product','ProductId')
            ->Where('productvariant','productid','=',$this->productId)
            ->OrderBy('ProductVariant','productid')
            ->ToString();

        $results = $db->Execute($select);

        $this->hasMultipleVariants = count($results) > 1 ? true : false;

        foreach($results as $result)
        {
            $this->variants->add((new ProductVariant())->Hydrate($result));

            if($result['isDefault'])
            {
                $this->defaultVariantId = $result['variantId'];
            }
        }
        //endregion

        //region ProductKit
        if($this->isAKit)
        {
            $db = new Database();
            $db->Connect(SFSERVER,SFUSER,SFPASS);
            $select = $db->Select((new KitGroup())->get('sfFields'),'KitGroup')
                ->Select('Name','KitGroupType','kitGroupType')
                ->UseDatabase(SFDATABASE)
                ->From('KitGroup')
                ->Join('inner','KitGroup','KitGroupType','KitGroupTypeId')
                ->Where('KitGroup','ProductId','=',$this->productId)
                ->OrderBy('KitGroup','KitGroupId')
                ->ToString();

            $results = $db->Execute($select);


            foreach($results as $result)
            {
                $this->kit->add((new KitGroup())->Hydrate($result));
            }
        }
        //endregion






        return $this;
    }
}