<?php
namespace App\Listeners;
use Phalcon\Events\Event;

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
        
        if ($settings[0]->title_optimization=='With Tags') {
            $postdata['product_name'] = $postdata['product_name']."+".$postdata['tags'];
        }
        if($postdata->price == ''){
            $postdata['price'] = $settings[0]->default_price;
        }
        if($postdata->stock == ''){
            $postdata['stock'] = $settings[0]->default_stock;
        }
    
        return $postdata;
    }
    public function beforeHandleRequest(Event $event, \Phalcon\Mvc\Application $application)
    {
       
            $aclFile = APP_PATH . '/security/acl.cache';
            if (true !== is_file($aclFile)) {
                $acl = new Memory();
                $ACL = Acl::find();
                foreach ($ACL as $k => $v) {
                    $acl->addRole($v->role);
                    $acl->addComponent(
                        $v->selectController,
                        [
                            $v->selectAction
                        ]
                    );
                    $acl->allow($v->role,  $v->selectController, $v->selectAction);
                }
                file_put_contents($aclFile, serialize($acl));
            } else {
                $acl = unserialize(file_get_contents($aclFile));
            }
            $role = $application->request->get('role');
            $controller = $application->router->getControllerName();
            $action = $application->router->getActionName();
            if (!$role || true !== $acl->isAllowed($role, $controller, $action)) {
                echo "Access denied :(";
                die();
            // } else {
            //     echo "we don't find any acl list try after somtiome";
            }
        }
    }

