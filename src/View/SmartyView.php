<?php

declare(strict_types=1);

namespace App\View;

use Smarty\Smarty;

final class SmartyView
{
    private readonly Smarty $smarty;

    public function __construct(string $rootPath, bool $debug)
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($rootPath . '/templates');
        $this->smarty->setCompileDir($rootPath . '/storage/templates');
        $this->smarty->setEscapeHtml(true);
        $this->smarty->setCompileCheck($debug ? Smarty::COMPILECHECK_ON : Smarty::COMPILECHECK_OFF);
    }

    /**
     * Render a template
     */
    public function render(string $template, array $data = []): string
    {
        $tpl = $this->smarty->createTemplate($template);
        $tpl->assign($data);

        return $tpl->fetch();
    }
}
