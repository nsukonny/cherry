<nav aria-label="Хлебные крошки">
    <ol class="breadcrumbs {$mod|default:''}">
        <li class="breadcrumbs__item"><a class="breadcrumbs__link" href="/">Главная</a></li>
        {foreach $crumbs|default:[] as $crumb}
            <li class="breadcrumbs__item"><a class="breadcrumbs__link" href="{$crumb.url}">{$crumb.title}</a></li>
        {/foreach}
        <li class="breadcrumbs__item" aria-current="page">{$current|default:$title}</li>
    </ol>
</nav>
