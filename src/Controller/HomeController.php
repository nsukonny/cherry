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
        return $this->render('pages/home.tpl', [
            'title' => 'Cherry — блог о сортах вишни',
            'description' => 'Описания сортов вишни, сравнения и советы по выбору и уходу.',
            'posts' => $this->repositories->posts()->findLatestPerCategory(3),
        ]);
    }
}
