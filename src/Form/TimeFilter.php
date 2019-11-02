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
use Zend\InputFilter\InputFilter;

class TimeFilter extends InputFilter
{
    public function __construct($option = [])
    {
        // date
        if ($option['isNew']) {
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
        }

        // start
        $this->add(
            [
                'name'       => 'start',
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

        // end
        $this->add(
            [
                'name'       => 'end',
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