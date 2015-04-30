<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/19/2015
 * Time: 5:30 PM
 */

namespace lib\Model;

abstract class Product
{
    protected $originId;
    protected $name;
    protected $sku;
    protected $description;
    protected $metaKeywords;
    protected $metaDescription;
    protected $metaTitle;
    protected $urlKey;
    protected $weight;
    protected $imagePaths;
}