<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/24/2015
 * Time: 9:38 AM
 */

namespace app\Storefront\Model;


class KitGroup extends \lib\Model\Data
{
    //region vars
    protected $productId;
    protected $kitGroupId;
    protected $name;
    protected $description;
    protected $isRequired;
    protected $displayOrder;
    protected $kitItems;
    protected $kitGroupType;
    protected $sfFields;
    //endregion


    function __construct()
    {
        $this->productId    = 0;
        $this->kitGroupId   = 0;
        $this->name         = '';
        $this->description  = '';
        $this->isRequired   = false;
        $this->displayOrder = 0;
        $this->kitItems     = new \lib\Model\Collection();
        $this->kitGroupType = '';
        $this->sfFields     = array(
                                'productId',
                                'kitGroupId',
                                'name',
                                'description',
                                'isRequired',
                                'displayOrder'
                            );

        return $this;
    }


    public function Hydrate($data)
    {
        //region KitGroupData
        foreach($data as $key => $value)
        {
            $this->$key = $value;
        }
        //endregion

        //region KitItems
        $db = new Database();
        $db->Connect(SFSERVER,SFUSER,SFPASS);
        $select = $db->Select((new KitItem())->get('sfFields'),'KitItem')
            ->UseDatabase(SFDATABASE)
            ->From('KitItem')
            ->Join('inner','KitItem','KitGroup','KitGroupId')
            ->Where('KitItem','KitGroupId','=',$this->kitGroupId)
            ->OrderBy('KitItem','KitGroupId')
            ->ToString();

        $results = $db->Execute($select);


        foreach($results as $result)
        {
            $this->kitItems->add((new KitItem())->Hydrate($result));
        }

        //endregion

        return $this;
    }

}