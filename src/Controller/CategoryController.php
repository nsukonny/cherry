<?php

namespace App\Controller;

use App\Http\HttpException;
use App\Http\Request;
use App\Http\Response;
use App\Repository\PostSort;
use App\Repository\Repositories;
use App\View\Pagination;
use App\View\SmartyView;

final class CategoryController extends BaseController
{
    private const PER_PAGE = 3;

    public function __construct(SmartyView $view, Repositories $repositories)
    {
        parent::__construct($view, $repositories);
    }

    /**
     * Draw Category Page: /{category}/?sort=views&page=2
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $category = $this->repositories->categories()->findBySlug($request->categorySlug)
            ?? throw HttpException::notFound();

        // Unknown ?sort= falls back to the default instead of an error.
        $sort = PostSort::tryFrom($request->query('sort') ?? '') ?? PostSort::Date;
        $path = '/' . $category['slug'] . '/';

        $pagination = new Pagination(
            current: $this->pageNumber($request),
            total: $this->repositories->posts()->countByCategory($category['id']),
            perPage: self::PER_PAGE,
            path: $path,
            query: $sort === PostSort::Date ? [] : ['sort' => $sort->value],
        );

        if (!$pagination->exists()) {
            throw HttpException::notFound();
        }

        return $this->render('pages/category.tpl', [
            'title' => $category['title'],
            'description' => $category['description'],
            'activeCategory' => $category['slug'],
            'posts' => $this->repositories->posts()->findByCategory(
                $category['id'],
                $sort,
                self::PER_PAGE,
                $pagination->offset(),
            ),
            'pagination' => $pagination,
            'sortLinks' => $this->sortLinks($sort, $path),
        ]);
    }

    /**
     * ?page= must be a positive integer; no parameter means page 1.
     * Anything else (abc, 0, -1, 2.5) becomes 0 and turns into 404.
     */
    private function pageNumber(Request $request): int
    {
        $raw = $request->query('page');

        if ($raw === null) {
            return 1;
        }

        $page = filter_var($raw, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        return $page === false ? 0 : $page;
    }

    /**
     * Switching the sort starts from page 1, so links carry only ?sort=.
     *
     * @return list<array{label: string, url: string, active: bool}>
     */
    private function sortLinks(PostSort $current, string $path): array
    {
        return array_map(
            static fn(PostSort $sort): array => [
                'label' => $sort->label(),
                'url' => $sort === PostSort::Date ? $path : $path . '?sort=' . $sort->value,
                'active' => $sort === $current,
            ],
            PostSort::cases(),
        );
    }
}
