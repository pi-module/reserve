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

namespace Module\Reserve\Form;

use Pi;
use Laminas\InputFilter\InputFilter;

class StatusFilter extends InputFilter
{
    public function __construct($option = [])
    {
        // payment_status
        $this->add(
            [
                'name'     => 'payment_status',
                'required' => true,
            ]
        );

        // reserve_status
        $this->add(
            [
                'name'     => 'reserve_status',
                'required' => true,
            ]
        );
    }
}