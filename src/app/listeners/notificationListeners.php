<?php
// namespace App\Listeners;
use Phalcon\Events\Event;

class notificationListeners
{
    public function afterSend($e)
    {
        $postdata = $e->getdata();
        // die($postdata);
        $orders = new Orders();
        $settings = Settings::find();
        if ($settings[0]->default_zipcode && $postdata->zipcode == '') {
            $postdata['zipcode'] = $settings[0]->default_zipcode;
        }
        return $postdata;
    }
}
