<?php

use Phalcon\Mvc\Controller;


class ProductController extends Controller
{

    public function IndexAction()
    {
    }

    public function addAction()
    {
        $products = new Products();
        if ($this->request->getpost()) {
            $products->assign(
                $postdata = $this->request->getPost(),
                [
                    'product_name',
                    'product_description',
                    'tags',
                    'price',
                    'stock'
                ]
            );
            if (
                empty($postdata['product_name']) || empty($postdata['product_description']) || empty($postdata['tags']) ||
                empty($postdata['price']) || empty($postdata['stock'])
            ) {
                $this->view->msg = "*Please fill all fields";
            } else {
                $products->save();
                $this->view->msg = "*Product added Successfully!!";
            }
            // $success = $products->save();
        }
    }
    public function listAction()
    {
        $products = new Products();
        $this->view->products = Products::find();
    }
}
