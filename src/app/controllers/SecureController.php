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
            // $acl->addRole('manager');
            $acl->addComponent(
                'test',
                [
                    'eventtest'
                ]
            );
            $acl->addComponent(
                'index',
                [
                    'index'
                ]
            );
            $acl->addComponent(
                'order',
                [
                    'index',
                    'add',
                    'list'
                ]
            );
            $acl->addComponent(
                'product',
                [
                    'index',
                    'add',
                    'list'
                ]
            );
            $acl->addComponent(
                'settings',
                [
                    'index'
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
            $this->view->roles = $acl->getRoles() ?? [];
            //die(print_r($acl->getRoles()));
            // $this->view->roles = $acl->getRoles() ?? [];
            if ($this->request->isPost()) {
                $postdata = $this->request->getpost();
                if ($postdata['role'] == '') {
                    $this->view->msg = '*Please enter a valid role !!';
                } else {
                    $ACL = new Acl();
                    $ACL->assign(
                        $postdata,
                        [
                            'role',

                        ]
                    );
                    $ACL->controller = null;
                    $ACL->action = null;
                    $success = $ACL->save();
                    $acl->addRole(new Role($postdata['role']));
                    file_put_contents($aclFile, serialize($acl));
                    if ($success) {
                        $this->view->msg = '*Role added successfully !!';
                        $this->view->success = $success;
                    } else {
                        $this->view->msg = '*Not Saved !!';
                    }
                    // $this->view->roles = $acl->getRoles() ?? [];
                }
            }
        }
    }
    public function addcomponentAction()
    {
        // return 'add component';
        $this->view->roles = Acl::find();
        $aclFile = APP_PATH . '/security/acl.cache';
        if (true !== is_file($aclFile)) {
            $acl = new Memory();
        } else {
            $acl = unserialize(file_get_contents($aclFile));
            // $this->view->roles = $acl->getComponents() ?? [];
            //die(print_r($acl->getRoles()));
            // $this->view->roles = $acl->getRoles() ?? [];
            if ($this->request->isPost()) {
                $postdata = $this->request->getpost();
                if ($postdata['controller'] == '' || $postdata['action'] == '') {
                    $this->view->message = '*Please enter all fields!!';
                } else {
                    $ACL = new Acl();
                    $ACL->assign(
                        $postdata,
                        [
                            'role',
                            'controller',
                            'action'

                        ]
                    );
                   
                    $success = $ACL->save();
                    $acl->addComponent(
                        $postdata["controller"],
                        [
                            $postdata["action"]    
                        ]
                    );
                    file_put_contents($aclFile, serialize($acl));
                    if ($success) {
                        $this->view->message = '*Component added successfully !!';
                        $this->view->success = $success;
                    } else {
                        $this->view->message = '*Components not Added !!';
                    }
                    // $this->view->roles = $acl->getRoles() ?? [];
                }
            }
        }
    }
}
