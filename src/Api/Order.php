<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Reserve\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Zend\Db\Sql\Predicate\Expression;

/*
 * Pi::api('order', 'reserve')->add($params);
 * Pi::api('order', 'reserve')->checkProduct($id, $type);
 * Pi::api('order', 'reserve')->getProductDetails($id);
 * Pi::api('order', 'reserve')->postPaymentUpdate($order, $basket);
 * Pi::api('order', 'reserve')->createExtraDetailForProduct($values);
 * Pi::api('order', 'reserve')->getExtraFieldsFormForOrder();
 */

class Order extends AbstractApi
{
    public function add($params)
    {
        // Set single product
        $singleProduct = [
            'product'        => $params['id'],
            'product_price'  => $params['amount'],
            'discount_price' => 0,
            'shipping_price' => 0,
            'packing_price'  => 0,
            'vat_price'      => 0,
            'number'         => 2,
            'title'          => $params['title'],
        ];

        // Set order array
        $order                           = [];
        $order['module_name']            = $this->getModule();
        $order['module_table']           = 'schedule';
        $order['type_payment']           = 'onetime';
        $order['type_commodity']         = 'service';
        $order['can_pay']                = 1;
        $order['total_discount']         = 0;
        $order['total_shipping']         = 0;
        $order['total_packing']          = 0;
        $order['total_setup']            = 0;
        $order['total_vat']              = 0;
        $order['product'][$params['id']] = $singleProduct;

        // Set and go to order
        return Pi::api('order', 'order')->setOrderInfo($order);
    }

    public function checkProduct($id, $type = null)
    {
        $schedule = Pi::api('schedule', 'reserve')->getSchedule($id);
        if (empty($schedule) || intval($schedule['reserve_status']) === 0) {
            return false;
        }
        return true;
    }

    public function getProductDetails($id)
    {
        // Get schedule
        $schedule = Pi::api('schedule', 'reserve')->getSchedule($id);

        // Set order product
        $productOrder = [
            'title'      => $schedule['title'],
            'productUrl' => $schedule['urlViewFront'],
            'thumbUrl'   => '',
        ];

        // return product
        return $productOrder;
    }

    public function postPaymentUpdate($order, $basket)
    {
        // Set basket
        $basket = array_shift($basket);

        // Get product
        $schedule = Pi::api('schedule', 'reserve')->getSchedule($basket['product']);

        // Update schedule
        Pi::model('schedule', $this->getModule())->update(
            [
                'payment_status' => 1,
                'order_id'       => $order['id'],
            ],
            [
                'id' => $schedule['id'],
            ]
        );

        // Set back url
        $url = Pi::url(
            Pi::service('url')->assemble(
                'order', [
                    'module'     => 'order',
                    'controller' => 'detail',
                    'action'     => 'index',
                    'id'         => $order['id'],
                ]
            )
        );

        return $url;
    }

    public function createExtraDetailForProduct($values)
    {
        return json_encode(
            [
                'item' => $values['module_item'],
            ]
        );
    }

    public function getExtraFieldsFormForOrder()
    {
        return [];
    }
}