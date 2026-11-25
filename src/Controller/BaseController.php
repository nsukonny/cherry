<?php
/**
 * Base controller for render header, footer and other main views.
 */

namespace App\Controller;

use App\Http\Response;
use App\View\SmartyView;

abstract class BaseController
{
    public function __construct(private readonly SmartyView $view)
    {
    }

    /**
     * Render a view with the given data.
     *
     * @param string $template The template file to render.
     * @param array $data The data to pass to the template.
     *
     * @return Response
     */
    protected function render(string $template, array $data = []): Response
    {
        return new Response($this->view->render($template, $data));
    }
}
