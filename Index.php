<?php
ini_set('display_errors', 1);
require_once('Config.php');


$storeFront = (new app\Storefront\Model\Storefront())->Hydrate();

$products = $storeFront->get('products')->get('objects');


foreach($products as $product)
{
    $mage = (new app\Magento\Model\Magento())->Hydrate($product);
}


foreach($mage->get('groupedproducts') as $groupedproduct)
{
    $newGrouped = Mage::getModel('catalog/product');

    try{
        foreach($groupedproduct as $key => $value)
        {
            if(!is_array($value))
            {
                $newGrouped->setData($key,$value);
            }
        }
        $newGrouped->setWebsiteIds(array(1)) //website ID the product is assigned to, as an array
        ->setAttributeSetId(4) //ID of a attribute set named 'default'
        ->setVisibility(4) //catalog and search visibility
//			->setMediaGallery (array('images'=>array (), 'values'=>array ())) //media gallery initialization
//			->addImageToMediaGallery('13.jpg', array('image','thumbnail','small_image'), false, false) //assigning image, thumb and small image to media gallery
        ->setStockData(array(
                'use_config_manage_stock' => 0, //'Use config settings' checkbox
                'manage_stock'=>1, //manage stock
                'min_sale_qty'=>1, //Minimum Qty Allowed in Shopping Cart
                'max_sale_qty'=>2, //Maximum Qty Allowed in Shopping Cart
                'is_in_stock' => 1, //Stock Availability
                'qty' => 999 //qty
            )
        );
        //->setCategoryIds($product->getCategoryIds()); //assign product to categories

        $newGrouped->getResource()->save($newGrouped);
        $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($newGrouped);
        $stockItem->setProductId($newGrouped->getEntityId());
        $stockItem->setStockId(1);
        $stockItem->setData('manage_stock', 1);
        $stockItem->setData('is_in_stock', 1);
        $stockItem->save();

        foreach($groupedproduct->get('associatedproducts') as $associate)
        {
            unset($new);
            unset($newStockItem);
            $newStockItem = Mage::getModel('cataloginventory/stock_item');
            $new = Mage::getModel('catalog/product');
            $associateId = \app\Magento\Helper\SimpleUp::SimpleUp($associate,$new,$newStockItem,TRUE);
            $products_links = Mage::getModel('catalog/product_link_api');
            $products_links->assign('grouped',$newGrouped->getEntityId(),$associateId);
        }
    }
    catch(Exception $e)
    {
        echo $e->getMessage() . " - " . $groupedproduct->get('name') . "<br />";
    }
}

foreach($mage->get('simpleProducts') as $simpleProduct)
{
    $newSimple = Mage::getModel('catalog/product');
    $newStockItemSimple = Mage::getModel('cataloginventory/stock_item');

    \app\Magento\Helper\SimpleUp::SimpleUp($simpleProduct,$newSimple,$newStockItemSimple);
}

echo "Yay";
die();
foreach($products as $p)
{
    unset($new);
	$new = Mage::getModel('catalog/product');

	try{
		$new
			->setWebsiteIds(array(1)) //website ID the product is assigned to, as an array
			->setAttributeSetId(4) //ID of a attribute set named 'default'
			->setTypeId('simple') //product type
			->setCreatedAt(strtotime('now')) //product creation time
			->setSku($product->getSku() . '-IMPORT') //SKU
			->setName($product->getName()) //product name
			->setWeight($product->getWeight())
			->setStatus(1) //product status (1 - enabled, 2 - disabled)
			->setTaxClassId(4) //tax class (0 - none, 1 - default, 2 - taxable, 4 - shipping)
			->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH) //catalog and search visibility
			->setPrice($product->getPrice()) //price in form 11.22
			->setDescription($product->getDescription())
			->setShortDescription($product->getShortDescription())
			->setManufacturer($product->getManufacturer())
//			->setMediaGallery (array('images'=>array (), 'values'=>array ())) //media gallery initialization
//			->addImageToMediaGallery('media/catalog/product/' . $product->getImage(), array('image','thumbnail','small_image'), false, false) //assigning image, thumb and small image to media gallery
			->setStockData(array(
					'use_config_manage_stock' => 0, //'Use config settings' checkbox
					'manage_stock'=>1, //manage stock
					'min_sale_qty'=>1, //Minimum Qty Allowed in Shopping Cart
					'max_sale_qty'=>2, //Maximum Qty Allowed in Shopping Cart
					'is_in_stock' => 1, //Stock Availability
					'qty' => 999 //qty
				)
			)
			->setCategoryIds($product->getCategoryIds()); //assign product to categories

        		$values = array();


        //kititems
		foreach($attribute->getPrices() as $price)
		{
			array_push($values,array(
				'title' => $price['label'],
				'price' => $price['pricing_value'],
				'price_type' => 'fixed',
				'sku' => '',
				'sort_order' => '1'
				)
			);
		}
        //kititems




        //kitgroups
		array_push($options,array(
			'title' => $attribute->getLabel(),
			'type' => 'drop_down', // could be drop_down ,checkbox , multiple
			'is_require' => 1,
			'sort_order' => 0,
			'values' => $values
		));
        //kitgroups


			Mage::getSingleton('catalog/product_option')->unsetOptions();
			$new->setProductOptions($options);
			$new->setCanSaveCustomOptions(true)
			->setHasOptions(true);




		$new->save();
	}catch(Exception $e){
		echo $e->getMessage() . " - " . $product->getName() . "<br />";
	}
}



die();