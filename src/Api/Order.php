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
        // Find address
        $addressList = Pi::api('customerAddress', 'order')->findAddresses($params['user_id']);

        // Add address
        if (empty($addressList)) {
            // Set user fields
            $fields = [
                'id', 'identity', 'name', 'email', 'first_name', 'last_name', 'id_number', 'phone', 'mobile', 'credit',
                'address1', 'address2', 'country', 'state', 'city', 'zip_code', 'company', 'company_id', 'company_vat',
            ];

            // Get user info
            $user = Pi::user()->get($params['user_id'], $fields);

            // Set address value
            $address = [
                'uid'          => $user['id'],
                'id_number'    => empty($user['id_number']) ? '' : $user['id_number'],
                'first_name'   => empty($user['first_name']) ? '' : $user['first_name'],
                'last_name'    => empty($user['last_name']) ? '' : $user['last_name'],
                'email'        => empty($user['email']) ? '' : $user['email'],
                'phone'        => empty($user['phone']) ? '' : $user['phone'],
                'mobile'       => empty($user['mobile']) ? '' : $user['mobile'],
                'address1'     => empty($user['address1']) ? '' : $user['address1'],
                'address2'     => empty($user['address2']) ? '' : $user['address2'],
                'country'      => empty($user['country']) ? '' : $user['country'],
                'state'        => empty($user['state']) ? '' : $user['state'],
                'city'         => empty($user['city']) ? '' : $user['city'],
                'zip_code'     => empty($user['zip_code']) ? '' : $user['zip_code'],
                'company'      => empty($user['company']) ? '' : $user['company'],
                'company_id'   => empty($user['company_id']) ? '' : $user['company_id'],
                'company_vat'  => empty($user['company_vat']) ? '' : $user['company_vat'],
                'time_create'  => time(),
                'time_update'  => time(),
                'ip'           => Pi::user()->getIp(),
                'status'       => 1,
                'delivery'     => 0,
                'location'     => 0,
                'account_type' => 'individual',
            ];

            // Add address
            Pi::api('customerAddress', 'order')->addAddress($address);
        }

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
                'reserve_status' => 1,
                'order_id'       => $order['id'],
            ],
            [
                'id' => $schedule['id'],
            ]
        );

        return $schedule['urlViewFront'];
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

    public function isAlwaysAvailable($order)
    {
        return array(
            'status' => 1
        );
    }
}
