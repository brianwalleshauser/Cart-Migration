<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/31/2015
 * Time: 3:54 PM
 */

namespace app\Storefront\Model;

define('SFURL','http://www.laceheaven.com/');
define('SFDATABASE','laceheaven');
define('SFSERVER','mainsql.mainstreethost.net');
define('SFUSER','bwalleshauser');
define('SFPASS','4g7Pq@');

class Storefront extends \lib\Model\Data
{
    protected $products;

    function __construct()
    {
        $this->products = new \lib\Model\Collection();
    }


    public function Hydrate()
    {
        $db = new Database();
        $db->Connect(SFSERVER,SFUSER,SFPASS);

        $select = $db->Select((new Product())->get('sfFields'),'product')
            ->UseDatabase(SFDATABASE)
            ->From('product')
            //->Where('product','isakit','=','1')
            ->Where('product','productid','=','75')
            ->OrderBy('product','productid')
            ->Limit(1)
            ->ToString();

        $results = $db->Execute($select);

        $i = 0;
        foreach($results as $result)
        {
            $this->products->add((new Product())->Hydrate($result));
        }

        return $this;
    }
}