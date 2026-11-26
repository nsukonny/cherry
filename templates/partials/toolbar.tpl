{* Счётчик статей и переключатель сортировки. Ожидает $pagination и $sortLinks. *}
<div class="toolbar">
    <p class="toolbar__count">
        <span class="toolbar__count-num">{$pagination->total}</span>
        {$pagination->total|plural:'статья':'статьи':'статей'} · страница {$pagination->current} из {$pagination->pages}
    </p>
    <nav class="sort" aria-label="Сортировка статей">
        <span class="sort__label">Сортировка</span>
        <ul class="sort__list">
            {foreach $sortLinks as $link}
                <li>
                    {if $link.active}
                        <a class="sort__link sort__link--active" href="{$link.url}" aria-current="true">{$link.label}</a>
                    {else}
                        <a class="sort__link" href="{$link.url}">{$link.label}</a>
                    {/if}
                </li>
            {/foreach}
        </ul>
    </nav>
</div>
