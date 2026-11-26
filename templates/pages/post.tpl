{extends file="layouts/base.tpl"}

{block name="content"}
    {$categoryUrl = "/{$currentCategory.slug}/"}

    <article>
        <header class="page-hero page-hero--center">
            <img class="page-hero__blob page-hero__blob--3" src="/assets/img/blob-3.svg" alt="" width="64" height="48">
            <img class="page-hero__blob page-hero__blob--2" src="/assets/img/blob-2.svg" alt="" width="64" height="48">

            <div class="page-hero__inner">
                <div class="page-hero__content">
                    {include file="partials/breadcrumbs.tpl"
                        crumbs=[['title' => $currentCategory.title, 'url' => $categoryUrl]]
                        current=$post.title
                        mod="breadcrumbs--center"}

                    <div class="page-hero__chips">
                        {foreach $post.categories as $postCategory}
                            <a class="chip chip--lg" href="/{$postCategory.slug}/">{$postCategory.title}</a>
                        {/foreach}
                    </div>

                    <h1 class="page-hero__title">{$post.title}</h1>
                    <p class="page-hero__text">{$post.description}</p>

                    <div class="page-hero__meta">
                        <span class="page-hero__meta-item">
                            {include file="partials/icons/calendar.tpl" class="page-hero__meta-icon"}
                            <time datetime="{$post.published_at|date_format:'%Y-%m-%d'}">{$post.published_at|date_format:'%d.%m.%Y'}</time>
                        </span>
                        <span class="page-hero__meta-item">
                            {include file="partials/icons/eye.tpl" class="page-hero__meta-icon"}
                            {$post.views|number_format:0:',':' '} {$post.views|plural:'просмотр':'просмотра':'просмотров'}
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <div class="cover">
            <figure class="cover__figure">
                {if $post.image}
                    <img class="cover__image" src="{$post.image}" alt="{$post.title}">
                {else}
                    <img class="cover__placeholder" src="/assets/img/cherries.svg" alt="" width="200" height="200">
                    <figcaption class="cover__caption">Изображение статьи</figcaption>
                {/if}
                <img class="cover__spark" src="/assets/img/spark.svg" alt="" width="24" height="24">
            </figure>
        </div>

        {* content — HTML из нашей базы (сидер), поэтому выводится без экранирования *}
        <div class="content">
            {$post.content nofilter}
        </div>
    </article>

    {if $related}
        <section class="related" aria-labelledby="related-title">
            <div class="section-head">
                <h2 class="section-head__title" id="related-title">Похожие <span class="section-head__accent">статьи</span></h2>
                <a class="button" href="{$categoryUrl}">
                    Все статьи
                    {include file="partials/icons/arrow.tpl" class="button__icon"}
                </a>
            </div>
            <div class="posts-grid">
                {foreach $related as $relatedPost}
                    {include file="partials/post-card.tpl" post=$relatedPost}
                {/foreach}
            </div>
        </section>
    {/if}
{/block}
