<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Mengarahkan ke file view landing_page.php
        return view('landing_page');
    }
}
