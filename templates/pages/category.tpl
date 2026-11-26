{extends file="layouts/base.tpl"}

{block name="content"}
    <section class="page-hero">
        {include file="partials/page-hero-blobs.tpl"}

        <div class="page-hero__inner">
            <div class="page-hero__content">
                {include file="partials/breadcrumbs.tpl"}
                <h1 class="page-hero__title">{$title}</h1>
                <p class="page-hero__text">{$description}</p>
            </div>
        </div>
    </section>
    <div class="ticker" aria-hidden="true">
        {include file="partials/ticker-row.tpl" mod="ticker__row--pink" a="МОРОЗОСТОЙКИЕ" b="ЗИМОСТОЙКИЕ"}
    </div>
    {include file="partials/toolbar.tpl"}
    <section class="posts" aria-label="Статьи категории">
        <div class="posts-grid">
            {foreach $posts as $post}
                {include file="partials/post-card.tpl" post=$post}
            {foreachelse}
                <p>В этой категории пока нет статей.</p>
            {/foreach}
        </div>
        {include file="partials/pagination.tpl"}
    </section>
{/block}
