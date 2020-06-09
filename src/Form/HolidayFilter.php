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

class HolidayFilter extends InputFilter
{
    public function __construct($option = [])
    {
        // date
        $this->add(
            [
                'name'       => 'date',
                'required'   => true,
                'filters'    => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    new \Module\Reserve\Validator\SetValue,
                ],
            ]
        );

        // provider_id
        $this->add(
            [
                'name'       => 'provider_id',
                'required'   => true,
                'validators' => [
                    new \Module\Reserve\Validator\SetValue,
                ],
            ]
        );
    }
}