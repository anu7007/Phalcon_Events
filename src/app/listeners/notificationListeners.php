<?php

namespace App\Listeners;

use Phalcon\Events\Event;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;
class notificationListeners
{
    public function afterSend($e)
    {
        $postdata = $e->getData();
        // die($postdata);
        $orders = new Orders();
        $settings = Settings::find();
        if ($settings[0]->default_zipcode && $postdata->zipcode == '') {
            $postdata['zipcode'] = $settings[0]->default_zipcode;
        }
        return $postdata;
    }
    public function beforeSend($e)
    {
        $postdata = $e->getData();
        // $proDucts = Products::find();
        $settings = Settings::find();

        if ($settings[0]->title_optimization == 'With Tags') {
            $postdata['product_name'] = $postdata['product_name'] . "+" . $postdata['tags'];
        }
        if ($postdata->price == '') {
            $postdata['price'] = $settings[0]->default_price;
        }
        if ($postdata->stock == '') {
            $postdata['stock'] = $settings[0]->default_stock;
        }

        return $postdata;
    }
    public function beforeHandleRequest(Event $event, \Phalcon\Mvc\Application $application)
    {
        $aclFile = APP_PATH . '/security/acl.cache';
        if (true !== is_file($aclFile)) {
            $acl = new Memory();
        } else {
            $acl = unserialize(
                file_get_contents($aclFile)
            );

            $role = $application->request->getQuery('role');
            $controller = $application->router->getControllerName();
            $action = $application->router->getActionName();
            if (!$role || true !== $acl->isAllowed($role, $controller, $action)) {
                echo "Access denied :(";
                die();
            } else {
                // echo "we don't find any acl list try after somtiome";
            }
        }
    }
}
