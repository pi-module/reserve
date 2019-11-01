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

namespace Module\Reserve\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Module\Reserve\Form\HolidayForm;
use Module\Reserve\Form\HolidayFilter;
use Zend\Db\Sql\Predicate\Expression;

class HolidayController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');
        $page   = $this->params('page', 1);

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Set info
        $holidayList = [];
        $where       = [];
        $order       = ['date DESC', 'id DESC'];
        $limit       = intval($config['admin_perpage']);
        $offset      = (int)($page - 1) * $limit;

        // Get info
        $select = $this->getModel('holiday')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('holiday')->selectWith($select);

        // Make list
        foreach ($rowset as $row) {
            $holidayList[$row->id] = Pi::api('holiday', 'reserve')->canonizeHoliday($row);
        }

        // Get count
        $count  = ['count' => new Expression('count(*)')];
        $select = $this->getModel('holiday')->select()->columns($count);
        $count  = $this->getModel('holiday')->selectWith($select)->current()->count;

        // Set paginator
        $paginator = Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($config['admin_perpage']);
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(
            [
                'router' => $this->getEvent()->getRouter(),
                'route'  => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
                'params' => array_filter(
                    [
                        'module'     => $this->getModule(),
                        'controller' => 'holiday',
                        'action'     => 'index',
                    ]
                ),
            ]
        );

        // Set view
        $this->view()->setTemplate('holiday-index');
        $this->view()->assign('config', $config);
        $this->view()->assign('holidayList', $holidayList);
        $this->view()->assign('paginator', $paginator);
    }

    public function updateAction()
    {
        // Get info from url
        $module = $this->params('module');
        $id     = $this->params('id');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Set option
        $option = [];

        // Set form
        $form = new HolidayForm('holiday', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new HolidayFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                // Save values
                if (!empty($id)) {
                    $row = $this->getModel('holiday')->find($id);
                } else {
                    $row = $this->getModel('holiday')->createRow();
                }
                $row->assign($values);
                $row->save();

                // Jump
                $message = __('Holiday data saved successfully.');
                $this->jump(['action' => 'index'], $message, 'success');
            }
        } else {
            if ($id) {
                $holiday = Pi::api('holiday', 'reserve')->getHoliday($id);
                $form->setData($holiday);
            }
        }

        // Set view
        $this->view()->setTemplate('holiday-update');
        $this->view()->assign('config', $config);
        $this->view()->assign('form', $form);
    }
}