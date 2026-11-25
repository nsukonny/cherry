<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\View\SmartyView;

final class HomeController extends BaseController
{
    public function __construct(SmartyView $view)
    {
        parent::__construct($view);
    }

    /**
     * Draw Home Page
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->render('pages/home.tpl', [
            'title' => 'Welcome to Cherry',
            'message' => 'This is the home page of the Cherry application.',
        ]);
    }
}
