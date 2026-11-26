<?php

declare(strict_types=1);

namespace App\View;

use Smarty\Smarty;

final class SmartyView
{
    private readonly Smarty $smarty;

    public function __construct(string $rootPath, bool $debug = false)
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($rootPath . '/templates');
        $this->smarty->setCompileDir($rootPath . '/storage/templates');
        $this->smarty->setEscapeHtml(true);
        $this->smarty->setCompileCheck($debug ? Smarty::COMPILECHECK_ON : Smarty::COMPILECHECK_OFF);

        // {$count|plural:'статья':'статьи':'статей'}
        $this->smarty->registerPlugin(Smarty::PLUGIN_MODIFIER, 'plural', [self::class, 'plural']);
    }

    /**
     * Russian plural form: 1 статья, 2 статьи, 5 статей, 11 статей, 21 статья.
     */
    public static function plural(int $count, string $one, string $few, string $many): string
    {
        $mod100 = $count % 100;
        $mod10 = $count % 10;

        return match (true) {
            $mod100 >= 11 && $mod100 <= 14 => $many,
            $mod10 === 1 => $one,
            $mod10 >= 2 && $mod10 <= 4 => $few,
            default => $many,
        };
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
