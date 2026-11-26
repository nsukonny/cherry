{* Карточка статьи. *}
{$postUrl = "/{$post.categories.0.slug|default:''}/{$post.slug}/"}
<article class="post-card">
    <a class="post-card__cover" href="{$postUrl}" tabindex="-1" aria-hidden="true">
		<span class="post-card__plate post-card__plate--{$post.id % 3 + 1}">
			{include file='partials/icons/cherry.tpl' class='post-card__icon' stroke='1.4'}
		</span>
    </a>
    <span class="post-card__cats">{foreach $post.categories as $category}{$category.title}{if !$category@last} · {/if}{/foreach}</span>
    <h3 class="post-card__title">
        <a class="post-card__title-link" href="{$postUrl}">{$post.title}</a>
    </h3>
    <p class="post-card__text">{$post.description}</p>
    <div class="post-card__meta">
        <time datetime="{$post.published_at|date_format:'%Y-%m-%d'}">{$post.published_at|date_format:'%d.%m.%Y'}</time>
        <span class="post-card__views">
			{include file='partials/icons/eye.tpl' class='post-card__views-icon'}
			<span class="visually-hidden">Просмотров:</span> {$post.views|number_format:0:',':' '}
		</span>
    </div>
    <a class="button button--block" href="{$postUrl}" aria-label="Читать статью: {$post.title}">Читать статью</a>
</article>
