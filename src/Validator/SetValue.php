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

namespace Module\Reserve\Validator;

use Pi;
use Laminas\Validator\AbstractValidator;

class SetValue extends AbstractValidator
{
    const TAKEN = 'elementExists';

    /**
     * @var array
     */
    protected $messageTemplates = [];

    protected $options = [];

    /**
     * {@inheritDoc}
     */
    public function __construct($options = null)
    {
        $this->messageTemplates = [
            self::TAKEN => __('Please select element'),
        ];
        parent::__construct($options);
    }


    /**
     * Slug validate
     *
     * @param mixed $value
     * @param array $context
     *
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $this->setValue($value);
        $value = intval($value);
        if ($value > 0) {
            return true;
        } else {
            $this->error(static::TAKEN);
            return false;
        }
    }
}
