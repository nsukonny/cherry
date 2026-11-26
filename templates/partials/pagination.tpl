{* Пагинация. Ожидает $pagination (App\View\Pagination). При одной странице не выводится. *}
{if $pagination->pages > 1}
    <nav class="pagination" aria-label="Страницы">
        <ul class="pagination__list">
            <li>
                {if $pagination->hasPrevious()}
                    <a class="pagination__link" href="{$pagination->url($pagination->current - 1)}" aria-label="Предыдущая страница">
                        {include file="partials/icons/chevron-left.tpl" class="pagination__icon"}
                    </a>
                {else}
                    <span class="pagination__link pagination__link--disabled" aria-hidden="true">
                        {include file="partials/icons/chevron-left.tpl" class="pagination__icon"}
                    </span>
                {/if}
            </li>

            {foreach $pagination->numbers() as $number}
                <li>
                    {if $number === $pagination->current}
                        <a class="pagination__link pagination__link--current" href="{$pagination->url($number)}" aria-current="page">{$number}</a>
                    {else}
                        <a class="pagination__link" href="{$pagination->url($number)}">{$number}</a>
                    {/if}
                </li>
            {/foreach}

            <li>
                {if $pagination->hasNext()}
                    <a class="pagination__link" href="{$pagination->url($pagination->current + 1)}" aria-label="Следующая страница">
                        {include file="partials/icons/chevron-right.tpl" class="pagination__icon"}
                    </a>
                {else}
                    <span class="pagination__link pagination__link--disabled" aria-hidden="true">
                        {include file="partials/icons/chevron-right.tpl" class="pagination__icon"}
                    </span>
                {/if}
            </li>
        </ul>
    </nav>
{/if}
