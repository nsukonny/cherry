<?php

namespace App\Controller;

use App\Http\HttpException;
use App\Http\Request;
use App\Http\Response;
use App\Repository\Repositories;
use App\View\SmartyView;

final class PostController extends BaseController
{
    private const RELATED_LIMIT = 3;

    public function __construct(SmartyView $view, Repositories $repositories)
    {
        parent::__construct($view, $repositories);
    }

    /**
     * Draw Post Page: /{category}/{post}
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $category = $this->repositories->categories()->findBySlug($request->categorySlug)
            ?? throw HttpException::notFound();

        $post = $this->repositories->posts()->findBySlug($request->postSlug)
            ?? throw HttpException::notFound();

        if (!in_array($category['slug'], array_column($post['categories'], 'slug'), true)) {
            throw HttpException::notFound();
        }

        // HEAD comes from bots and link checkers, not readers: don't count it.
        if ($request->method === 'GET') {
            $this->repositories->posts()->incrementViews($post['id']);
            $post['views']++;
        }

        return $this->render('pages/post.tpl', [
            'title' => $post['title'],
            'description' => $post['description'],
            'activeCategory' => $category['slug'],
            'currentCategory' => $category,
            'post' => $post,
            'related' => $this->repositories->posts()->findRelated($post['id'], self::RELATED_LIMIT),
        ]);
    }
}
