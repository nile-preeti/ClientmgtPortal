<?php

use App\Models\UserService;

if (!function_exists("get_service_charge")) {
    function get_service_charge($user_id, $service_id)
    {

        // $charge = UserService::where("user_id", $user_id)->where("service_id", $service_id)->first();
        // if ($charge) {
        //     return   $charge->price_per_hour;
        // } else {
        //     return   0;
        // }
    }
}
