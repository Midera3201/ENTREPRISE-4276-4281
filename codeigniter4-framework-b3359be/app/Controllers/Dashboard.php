<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('dashboards/index');
    }

    public function admin()
    {
        return view('dashboards/admin');
    }

    public function rh()
    {
        return view('dashboards/rh');
    }

    public function employe()
    {
        return view('dashboards/employe');
    }
}
