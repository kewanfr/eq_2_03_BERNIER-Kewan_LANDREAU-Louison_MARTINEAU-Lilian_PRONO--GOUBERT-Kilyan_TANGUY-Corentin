<?php

namespace App\Controllers;

class LegalController extends BaseController
{
    public function mentionsLegales()
    {
        return view('legal/mentions_legales');
    }

    public function cgv()
    {
        return view('legal/cgv');
    }
}
