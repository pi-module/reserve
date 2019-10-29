<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt BSD 3-Clause License
 * @package         Registry
 */

/**
 * @author Mohammad Saltanatpouri <mysaltern@gmail.com>
 */

namespace Module\Reserve\Registry;

use Pi;
use Pi\Application\Registry\AbstractRegistry;

/*
 * Pi::registry('providerList', 'reserve')->clear();
 * Pi::registry('providerList', 'reserve')->read();
 */

class ProviderList extends AbstractRegistry
{
    /** @var string Module name */
    protected $module = 'b2b';

    /**
     * {@inheritDoc}
     * @param bool $name
     */
    public function create()
    {
        $this->clear('');
        $this->read();

        return true;
    }

    /**
     * {@inheritDoc}
     * @param array
     */
    public function read()
    {
        $options = [];
        $result  = $this->loadData($options);
        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function setNamespace($meta = '')
    {
        return parent::setNamespace('');
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        return $this->clear('');
    }

    /**
     * {@inheritDoc}
     */
    protected function loadDynamic($options = [])
    {
        return Pi::api('provider', 'Reserve')->getList();
    }
}
