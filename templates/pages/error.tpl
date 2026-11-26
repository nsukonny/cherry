{extends file="layouts/base.tpl"}

{block name="content"}
    <section class="page-hero">
        {include file="partials/page-hero-blobs.tpl"}

        <div class="page-hero__inner">
            <div class="page-hero__content">
                <span class="page-hero__badge">Ошибка {$code}</span>
                <h1 class="page-hero__title">Что-то пошло не так</h1>
                <p class="page-hero__text">Попробуйте обновить страницу чуть позже или вернитесь на главную.</p>
                <a class="button button--white" href="/">
                    На главную
                    {include file="partials/icons/arrow.tpl" class="button__icon"}
                </a>
            </div>
        </div>
    </section>
{/block}
