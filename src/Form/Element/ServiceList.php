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

namespace Module\Reserve\Form\Element;

use Pi;
use Laminas\Form\Element\Select;

class ServiceList extends Select
{
    /**
     * @return array
     */
    public function getValueOptions()
    {
        if (empty($this->valueOptions)) {
            // Get service list
            $serviceList = Pi::registry('serviceList', 'reserve')->read();
            foreach ($serviceList as $service) {
                $this->valueOptions[$service['id']] = $service['title'];
            }
        }
        return $this->valueOptions;
    }
}
