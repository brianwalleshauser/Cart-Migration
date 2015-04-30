<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/24/2015
 * Time: 1:33 PM
 */

namespace app\Storefront\Model;


class KitItem extends \lib\Model\Data
{
    //region vars
    protected $kitItemId;
    protected $kitGroupId;
    protected $name;
    protected $priceDelta;
    protected $isDefault;
    protected $displayOrder;
    protected $inventoryVariantId;
    protected $description;
    protected $sfFields;
    //endregion


    function __construct()
    {
        $this->kitItemId            = 0;
        $this->kitGroupId           = 0;
        $this->name                 = '';
        $this->priceDelta           = 0.00;
        $this->isDefault            = false;
        $this->displayOrder         = 0;
        $this->inventoryVariantId   = 0;
        $this->description          = '';
        $this->sfFields             = array(
                                        'kitItemId',
                                        'kitGroupId',
                                        'name',
                                        'priceDelta',
                                        'isDefault',
                                        'displayOrder',
                                        'inventoryVariantId',
                                        'description'
                                    );

        return $this;
    }


    public function Hydrate($data)
    {
        foreach($data as $key => $value)
        {
            $this->$key = $value;
        }

        return $this;
    }
}