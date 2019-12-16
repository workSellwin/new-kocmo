<?php

namespace Kocmo\Product;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class ImageTable
 *
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> product_id int optional
 * <li> from_1c int mandatory
 * <li> name_1c string(255) optional
 * <li> image string(255) optional
 * <li> updated datetime optional
 * <li> order_ int optional
 * <li> dtype string(255) mandatory
 * </ul>
 *
 * @package Bitrix\Product
 **/

class ImageTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'catalog_product_image';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'id' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('IMAGE_ENTITY_ID_FIELD'),
            ),
            'product_id' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('IMAGE_ENTITY_PRODUCT_ID_FIELD'),
            ),
            'from_1c' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('IMAGE_ENTITY_FROM_1C_FIELD'),
            ),
            'name_1c' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateName1c'),
                'title' => Loc::getMessage('IMAGE_ENTITY_NAME_1C_FIELD'),
            ),
            'image' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateImage'),
                'title' => Loc::getMessage('IMAGE_ENTITY_IMAGE_FIELD'),
            ),
            'updated' => array(
                'data_type' => 'datetime',
                'title' => Loc::getMessage('IMAGE_ENTITY_UPDATED_FIELD'),
            ),
            'order_' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('IMAGE_ENTITY_ORDER__FIELD'),
            ),
            'dtype' => array(
                'data_type' => 'string',
                'required' => true,
                'validation' => array(__CLASS__, 'validateDtype'),
                'title' => Loc::getMessage('IMAGE_ENTITY_DTYPE_FIELD'),
            ),
        );
    }
    /**
     * Returns validators for name_1c field.
     *
     * @return array
     */
    public static function validateName1c()
    {
        return array(
            new Main\Entity\Validator\Length(null, 255),
        );
    }
    /**
     * Returns validators for image field.
     *
     * @return array
     */
    public static function validateImage()
    {
        return array(
            new Main\Entity\Validator\Length(null, 255),
        );
    }
    /**
     * Returns validators for dtype field.
     *
     * @return array
     */
    public static function validateDtype()
    {
        return array(
            new Main\Entity\Validator\Length(null, 255),
        );
    }
}
