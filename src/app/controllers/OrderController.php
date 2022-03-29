<?php

use Phalcon\Mvc\Controller;


class OrderController extends Controller
{

    public function IndexAction()
    {
    }
    public function AddAction()
    {
        $orders = new Orders();
        $this->view->products = Products::find();
        if ($this->request->getpost()) {
            $orders->assign(
                $postdata = $this->request->getPost(),
                [
                    'customer_name',
                    'customer_address',
                    'zipcode',
                    'product_id',
                    'quantity'
                ]
            );
            if (
                empty($postdata['customer_name']) || empty($postdata['customer_address']) || empty($postdata['zipcode']) ||
                empty($postdata['product_id']) || empty($postdata['quantity'])
            ) {
                $this->view->ordermsg = "*Please fill all fields";
            } else {
                $orders->save();
                $this->view->ordermsg = "*Product added Successfully!!";
            }
            // $success = $products->save();
        }
        
    }
    public function listAction()
    {
        $orders = new Orders();
        $this->view->orders = Orders::find();
    }
}
