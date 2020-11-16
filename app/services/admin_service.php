<?php
namespace App\Services;

use App\Models\Model_Admin;

class Admin_Service
{

    public function checkAdmin()
    {
        if ( isset($_COOKIE['auth'])) {
            $admin = Model_Admin::where('password', $_COOKIE['auth'])->first();
            if ($admin)
                return true;
        }
        return false;
    }

}
