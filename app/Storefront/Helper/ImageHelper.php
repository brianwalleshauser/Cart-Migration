<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 3/23/2015
 * Time: 2:18 PM
 */

namespace app\Storefront\Helper;

class ImageHelper
{
    public static function GetImageByCurl($id,$sku)
    {
        if(GETIMAGES)
        {
            $fileextentions = array(
                'jpg',
                'jpeg',
                'gif',
                'png'
            );

//            for($i=0;$i<count($fileextentions);$i++)
//            {
//                $ch = curl_init(SFURL . 'images/product/large/' . $id . '.' . $fileextentions[$i]);
//                $filename = '/opt2/sites/CartMigration/images/product-' . $id . '_sku-' . $sku . '.' . $fileextentions[$i];
//                $fp = fopen($filename, 'wb');
//                curl_setopt($ch, CURLOPT_FILE, $fp);
//                curl_setopt($ch, CURLOPT_HEADER, 0);
//                curl_exec($ch);
//
//                if(strpos(curl_getinfo($ch, CURLINFO_CONTENT_TYPE),'image/') === false)
//                {
//                    unlink($filename);
//                }
//
//                curl_close($ch);
//                fclose($fp);
//            }

                    //try for sku image
            for($i=0;$i<count($fileextentions);$i++)
            {
                $ch = curl_init(SFURL . 'images/product/large/' . $sku . '.' . $fileextentions[$i]);

                if(!is_dir('/opt2/sites/CartMigration/images/' . $sku[0]))
                {
                    $oldmask = umask(0);
                    mkdir('/opt2/sites/CartMigration/images/' . $sku[0],0777);
                    umask($oldmask);

                    if(!is_dir('/opt2/sites/CartMigration/images/' . $sku[0]  . '/' . $sku[1]))
                    {
                        $oldmask = umask(0);
                        mkdir('/opt2/sites/CartMigration/images/' . $sku[0] . '/' . $sku[1],0777);
                        umask($oldmask);
                    }
                }

                $filename = '/opt2/sites/CartMigration/images/' . $sku[0] . '/' . $sku[1] . '/' . $sku . '.' . $fileextentions[$i];
                $fp = fopen($filename, 'wb');
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_exec($ch);

                if (strpos(curl_getinfo($ch, CURLINFO_CONTENT_TYPE), 'image/') === false) {
                    unlink($filename);
                }

                curl_close($ch);
                fclose($fp);
            }
        }
    }
}