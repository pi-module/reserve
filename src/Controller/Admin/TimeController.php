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
use Module\Reserve\Form\TimeForm;
use Module\Reserve\Form\TimeFilter;
use Laminas\Db\Sql\Predicate\Expression;

class TimeController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');
        $page   = $this->params('page', 1);

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Set info
        $timeList = [];
        $where    = [];
        $order    = ['date DESC', 'id DESC'];
        $limit    = intval($config['admin_perpage']);
        $offset   = (int)($page - 1) * $limit;

        // Get info
        $select = $this->getModel('time')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('time')->selectWith($select);

        // Make list
        foreach ($rowset as $row) {
            $timeList[$row->id] = Pi::api('time', 'reserve')->canonizeTime($row);
        }

        // Get count
        $count  = ['count' => new Expression('count(*)')];
        $select = $this->getModel('time')->select()->columns($count);
        $count  = $this->getModel('time')->selectWith($select)->current()->count;

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
                        'controller' => 'time',
                        'action'     => 'index',
                    ]
                ),
            ]
        );

        // Set view
        $this->view()->setTemplate('time-index');
        $this->view()->assign('config', $config);
        $this->view()->assign('timeList', $timeList);
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
        $option = [
            'isNew' => intval($id) > 0 ? false : true,
        ];

        // Set form
        $form = new TimeForm('time', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new TimeFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                // Save values
                if (!empty($id)) {
                    $row = $this->getModel('time')->find($id);
                } else {
                    $row = $this->getModel('time')->createRow();
                }
                $row->assign($values);
                $row->save();

                // Jump
                $message = __('Time data saved successfully.');
                $this->jump(['action' => 'index'], $message, 'success');
            }
        } else {
            if ($id) {
                $time = Pi::api('time', 'reserve')->getTime($id);
                $form->setData($time);
            }
        }

        // Set view
        $this->view()->setTemplate('time-update');
        $this->view()->assign('config', $config);
        $this->view()->assign('form', $form);
    }
}