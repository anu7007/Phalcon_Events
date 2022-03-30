<?php

use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;

class SecureController extends Controller
{
    public function initialize()
    {
        $aclFile = APP_PATH . '/security/acl.cache';
        if (true !== is_file($aclFile)) {
            $acl = new Memory();
            // print_r(json_encode($acl));

            $acl->addRole('admin');
            $acl->addRole('user');
            $acl->addRole('manager');
            $acl->addComponent(
                'test',
                [
                    'eventtest'
                ]
            );

            $acl->allow('admin', 'test', 'eventtest');
            $acl->deny('user', '*', '*');
            file_put_contents($aclFile, serialize($acl));
        } else {
            $acl = unserialize(file_get_contents($aclFile));
        }
    }
    public function indexAction()
    {
    }
    public function addroleAction()
    {
        $aclFile = APP_PATH . '/security/acl.cache';
        if (true !== is_file($aclFile)) {
            $acl = new Memory();
        } else {
            $acl = unserialize(file_get_contents($aclFile));
            // $this->view->roles = $acl->getRoles()??[];
            //die(print_r($acl->getRoles()));

            if ($this->request->isPost()) {
                $postdata = $this->request->getpost();
                if ($postdata['role'] == '') {
                    $this->view->msg = '*Please enter a valid role !!';
                } else {
                    $success = $acl->addRole(new Role($postdata['role']));
                    $this->view->msg = '*Role added successfully !!';
                    $this->view->success = $success;
                    file_put_contents($aclFile, serialize($acl));
                    $this->view->roles = $acl->getRoles() ?? [];
                }
            }
        }
    }
}
