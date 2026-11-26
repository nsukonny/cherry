<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Repository\PostSort;
use App\Repository\Repositories;
use App\View\SmartyView;

final class CategoryController extends BaseController
{
    public function __construct(SmartyView $view, Repositories $repositories)
    {
        parent::__construct($view, $repositories);
    }

    /**
     * Draw Category Page
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $currentCategory = $this->repositories->categories()->findBySlug($request->categorySlug);
        $sort = PostSort::Date;
        $limit = 10;
        $offset = 0;

        return $this->render('pages/category.tpl', [
            'title' => $currentCategory['title'],
            'description' => $currentCategory['description'],
            'posts' => $this->repositories->posts()->findByCategory($currentCategory['id'], $sort, $limit, $offset),
        ]);
    }
}
