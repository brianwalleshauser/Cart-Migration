<?php
/**
 * Created by PhpStorm.
 * User: bwalleshauser
 * Date: 4/1/2015
 * Time: 10:14 AM
 */

namespace app\Magento\Helper;

class Option
{
    public static function ConvertOptionType($oldType)
    {
        $typeArray = array(
            'Single Select Dropdown List' => 'drop_down',
            'Single Select Radio List' => 'radio',
            'Multi Select Checkbox' => 'checkbox',
            'Textbox' => 'field',
            'Text Area' => 'area',
            'File Upload' => 'file'
        );

        foreach($typeArray as $sfType => $mageType)
        {
            if($sfType === $oldType)
            {
                return $mageType;
            }
        }
    }
}