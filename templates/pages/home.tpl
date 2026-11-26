<!doctype html>
<html lang="ru">
{include file="partials/head.tpl" title="$title"}
<body>
<div class="page">
    {include file="partials/header.tpl"}

    <main class="page__main">
        <section class="page-hero">
            {include file="partials/page-hero-blobs.tpl"}

            <div class="page-hero__inner">
                <div class="page-hero__content">
                    <span class="page-hero__badge">Блог о сортах вишни</span>
                    <h1 class="page-hero__title">Узнай свою вишню в лицо</h1>
                    <p class="page-hero__text">Сладкие и кислые, старинные и новые, кустовые и морозостойкие — описания
                        сортов и советы, что посадить у себя.</p>
                    <a class="button button--white" href="#categories">
                        Читать статьи
                        {include file="partials/icons/arrow.tpl" class="button__icon"}
                    </a>
                </div>
                <div class="page-hero__visual" aria-hidden="true">
                    <div class="page-hero__plate">
                        <img class="page-hero__cherries" src="/assets/img/cherries.svg" alt="" width="200" height="200">
                        <img class="page-hero__spark" src="/assets/img/spark-pink.svg" alt="" width="24" height="24">
                    </div>
                </div>
            </div>

        </section>

        <div class="ticker" aria-hidden="true">
            {include file="partials/ticker-row.tpl" mod="ticker__row--outlined" a="ВИШНЯ" b="СОРТА"}
            {include file="partials/ticker-row.tpl" mod="ticker__row--pink" track="ticker__track--reverse" a="СЛАДКАЯ" b="КИСЛАЯ"}
        </div>

        <section class="intro" id="categories">
            <h2 class="intro__title">
                Сто сортов —
                <span class="intro__accent">сто характеров</span>
                <img class="intro__spark" src="/assets/img/spark.svg" alt="" width="24" height="24">
            </h2>
            <p class="intro__text">Выберите рубрику или начните с последних статей — в каждой категории три свежих
                материала.</p>
        </section>

        {* $posts — [id категории => статьи] из PostRepository::findLatestPerCategory() *}
        {$number = 0}
        {foreach $categories as $category}
            {$categoryPosts = $posts[$category.id] ?? []}
            {if $categoryPosts}
                {$number = $number + 1}
        <section class="category-block" aria-labelledby="cat-{$category.slug}">
            <div class="section-head">
                <div class="section-head__main">
                    <span class="section-head__num" aria-hidden="true">{$number|string_format:"%02d"}</span>
                    <div class="section-head__info">
                        <h2 class="section-head__title" id="cat-{$category.slug}">{$category.title}</h2>
                        <p class="section-head__text">{$category.description}</p>
                    </div>
                </div>
                <a class="button" href="/{$category.slug}/" aria-label="Все статьи категории «{$category.title}»">
                    Все статьи
                    {include file="partials/icons/arrow.tpl" class="button__icon"}
                </a>
            </div>
            <div class="posts-grid">
                {foreach $categoryPosts as $post}
                    {include file="partials/post-card.tpl" post=$post}
                {/foreach}
            </div>
        </section>
            {/if}
        {/foreach}
    </main>

    {include file="partials/footer.tpl"}
</div>
</body>
</html>
