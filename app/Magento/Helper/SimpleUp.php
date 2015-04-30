<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 4/2/2015
 * Time: 8:55 AM
 */

namespace app\Magento\Helper;

class SimpleUp
{
    public static function SimpleUp($simpleProduct,$new,$stockItem,$isAssociate = FALSE)
    {


        try{
            foreach($simpleProduct as $key => $value)
            {
                if(!is_array($value))
                {
                    $new->setData($key,$value);
                }
            }



            $new->setWebsiteIds(array(1)) //website ID the product is assigned to, as an array
            ->setAttributeSetId(4); //ID of a attribute set named 'default'
            if($isAssociate)
            {
                $new->setVisibility(1);
            }
            else
            {
                $new->setVisibility(4); //catalog and search visibility
            }


//			->setMediaGallery (array('images'=>array (), 'values'=>array ())) //media gallery initialization
//			->addImageToMediaGallery('13.jpg', array('image','thumbnail','small_image'), false, false) //assigning image, thumb and small image to media gallery
            $new->setStockData(array(
                    'use_config_manage_stock' => 0, //'Use config settings' checkbox
                    'manage_stock'=>1, //manage stock
                    'min_sale_qty'=>1, //Minimum Qty Allowed in Shopping Cart
                    //'max_sale_qty'=>2, //Maximum Qty Allowed in Shopping Cart
                    'is_in_stock' => 1, //Stock Availability
                    'qty' =>  $simpleProduct->get('qty')//qty
                ));
//            );
            //->setCategoryIds($product->getCategoryIds()); //assign product to categories

//            Mage::getSingleton('catalog/product_option')->unsetOptions();
//            $new->setProductOptions($simpleProduct->get('options'));
//            $new->setCanSaveCustomOptions(true)
//                ->setHasOptions(true);




            $new->getResource()->save($new);
            $qty = $simpleProduct->get('qty');
            $stockItem->loadByProduct($new);
            $stockItem->setProductId($new->getEntityId());
            $stockItem->setStockId(1);
            $stockItem->setData('manage_stock', 1);
            $stockItem->setData('is_in_stock', 1);
            $stockItem->setData('qty', $qty);
            $stockItem->setData('min_sale_qty', 1);
            $stockItem->save();

            return $new->getEntityId();
        }catch(Exception $e){
            echo $e->getMessage() . " - " . $simpleProduct->get('name') . "<br />";
        }

    }
}