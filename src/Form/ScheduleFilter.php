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

class ScheduleFilter extends InputFilter
{
    public function __construct($option = [])
    {
        // user_id
        if ($option['isNew'] && $option['section'] == 'admin') {
            $this->add(
                [
                    'name'       => 'user_id',
                    'required'   => true,
                    'validators' => [
                        new \Module\Reserve\Validator\SetValue,
                    ],
                ]
            );
        }

        // date
        $this->add(
            [
                'name'     => 'date',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    new \Module\Reserve\Validator\SetValue,
                ],
            ]
        );

        // hour
        $this->add(
            [
                'name'     => 'hour',
                'required' => true,
                'filters'  => [
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
}