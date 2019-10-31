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
use Zend\Form\Element\Select;

class ProviderList extends Select
{
    /**
     * @return array
     */
    public function getValueOptions()
    {
        if (empty($this->valueOptions)) {
            // Get provider list
            $providerList = Pi::registry('providerList', 'reserve')->read();
            foreach ($providerList as $provider) {
                $this->valueOptions[$provider['id']] = $provider['title'];
            }
        }
        return $this->valueOptions;
    }
}