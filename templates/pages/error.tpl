{extends file="layouts/base.tpl"}

{block name="content"}
    <section class="page-hero">
        {include file="partials/page-hero-blobs.tpl"}

        <div class="page-hero__inner">
            <div class="page-hero__content">
                <span class="page-hero__badge">Ошибка {$code}</span>
                {if $code === 404}
                    <h1 class="page-hero__title">Страница не найдена</h1>
                    <p class="page-hero__text">Такой страницы нет или она была удалена. Загляните в категории или вернитесь на главную.</p>
                {else}
                    <h1 class="page-hero__title">Что-то пошло не так</h1>
                    <p class="page-hero__text">Мы уже знаем о проблеме. Попробуйте обновить страницу чуть позже.</p>
                {/if}
                <a class="button button--white" href="/">
                    На главную
                    {include file="partials/icons/arrow.tpl" class="button__icon"}
                </a>
            </div>
        </div>
    </section>
{/block}
