<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Atoosa Rezaei <atoosa.rezaei@gmail.com>
 */

namespace Module\Reserve\Validator;

use Laminas\Validator\AbstractValidator;

class Amount extends AbstractValidator
{
    const AMOUNTNOTTRUE = 'amountNotTrue';

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
            self::AMOUNTNOTTRUE => __('Amount format not true'),
        ];
        parent::__construct($options);
    }

    /**
     * Element validate
     *
     * @param mixed $value
     * @param array $context
     *
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        // Set value
        $this->setValue($value);

        if (intval($value) < 1) {
            $this->error(static::AMOUNTNOTTRUE);
            return false;
        }

        return true;
    }
}