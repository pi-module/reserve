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
use Laminas\Db\Sql\Predicate\Expression;

class UserList extends Select
{
    /**
     * @return array
     */
    public function getValueOptions()
    {
        if (empty($this->valueOptions)) {
            // Set model
            $modelAccount = Pi::model('account', 'user');

            // Select
            $where  = ['account.active' => 1];
            $select = Pi::db()->select();
            $select->from(['account' => $modelAccount->getTable()]);
            $select->columns(['id', 'name']);
            $select->where($where);
            $rowSet = Pi::db()->query($select);

            // Set to value as option
            if (!empty($rowSet)) {
                $this->valueOptions[0] = '';
                foreach ($rowSet as $user) {
                    $this->valueOptions[$user['id']] = sprintf('%s - %s', $user['id'], $user['name']);
                }
            }
        }
        return $this->valueOptions;
    }
}
