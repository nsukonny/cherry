<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Repository\Repositories;
use App\View\SmartyView;

final class HomeController extends BaseController
{
    public function __construct(SmartyView $view, Repositories $repositories)
    {
        parent::__construct($view, $repositories);
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
        $posts = $this->repositories->posts()->findLatestPerCategory(10);
        return $this->render('pages/home.tpl', [
            'title' => 'Welcome to Cherry',
            'message' => 'This is the home page of the Cherry application.',
            'posts' => $posts,
        ]);
    }
}
