<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/19/2015
 * Time: 2:04 PM
 */

spl_autoload_register('Config::AutoLoad');
date_default_timezone_set('America/New_York');


define('DEBUG',TRUE);
define('GETIMAGES',FALSE);



        /* Magento */
define('MAGESERVER','10.1.208.248');
define('MAGEPATH','/opt2/sites/bubble/');
require_once(MAGEPATH . 'app/Mage.php'); //Path to Magento
umask(0);
Mage::app();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);




class Config
{
    const APP_PATH      = '';

    public static function AutoLoad($class)
    {
        $file        = null;
        $namespaced  = str_replace('\\', '/', $class);


                // Does namespaced class exist - then require it
                // Otherwise look for class through the specified directories
        if(file_exists(static::APP_PATH . $namespaced . '.php'))
        {
            $file = static::APP_PATH . $namespaced . '.php';
            require_once $file;
        }
        elseif($file === null)
        {
            foreach(static::Dirs() as $dir)
            {
                foreach(glob( $dir . '*.php') as $file)
                {
                    if($file ==  $dir . $class . '.php')
                    {
                        require_once  $file;  break;
                    }
                }
                unset($dir);
            }
        }
    }


    public static function Dirs()
    {
        return array(
            static::APP_PATH . 'app/',
            static::APP_PATH . 'app/Storefront/',
            static::APP_PATH . 'app/Storefront/Model/',
            static::APP_PATH . 'app/Storefront/Helper/',
            static::APP_PATH . 'lib/',
            static::APP_PATH . 'lib/Model/',
        );
    }
}