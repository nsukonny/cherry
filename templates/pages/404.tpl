{extends file="layouts/base.tpl"}

{block name="content"}
    <section class="page-hero">
        {include file="partials/page-hero-blobs.tpl"}

        <div class="page-hero__inner">
            <div class="page-hero__content">
                <span class="page-hero__badge">Ошибка 404</span>
                <h1 class="page-hero__title">Такой вишни у нас нет</h1>
                <p class="page-hero__text">Страница не найдена: возможно, ссылка устарела или в адресе опечатка.
                    Выберите категорию или вернитесь на главную.</p>

                {if $categories|default:[]}
                    <nav class="page-hero__chips" aria-label="Категории">
                        {foreach $categories as $category}
                            <a class="chip chip--lg" href="/{$category.slug}/">{$category.title}</a>
                        {/foreach}
                    </nav>
                {/if}

                <a class="button button--white" href="/">
                    На главную
                    {include file="partials/icons/arrow.tpl" class="button__icon"}
                </a>
            </div>
            <div class="page-hero__visual" aria-hidden="true">
                <div class="page-hero__plate">
                    <img class="page-hero__cherries" src="/assets/img/cherries.svg" alt="" width="200" height="200">
                </div>
            </div>
        </div>
    </section>
{/block}
