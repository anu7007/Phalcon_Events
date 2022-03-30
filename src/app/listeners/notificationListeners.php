<?php
// namespace App\Listeners;
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
}
