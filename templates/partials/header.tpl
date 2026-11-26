{* Шапка. Ожидает $categories (список категорий) и необязательный $activeCategory (slug текущей). *}
<header class="header">
	<div class="header__inner">
		<a class="header__logo" href="/" aria-label="Cherry — на главную">
			{include file='partials/icons/cherry.tpl' class='header__logo-icon' stroke='2'}
			<span class="header__logo-text">cherry</span>
		</a>
		<button class="header__burger" type="button" aria-expanded="false" aria-controls="header-nav" aria-label="Открыть меню">
			<span class="header__burger-line"></span>
			<span class="header__burger-line"></span>
			<span class="header__burger-line"></span>
		</button>
		<nav class="header__nav" id="header-nav" aria-label="Категории">
			{foreach $categories|default:[] as $category}
				{if $category.slug === ($activeCategory|default:'')}
			<a class="header__link header__link--active" href="/{$category.slug}/" aria-current="page">{$category.title}</a>
				{else}
			<a class="header__link" href="/{$category.slug}/">{$category.title}</a>
				{/if}
			{/foreach}
		</nav>
	</div>
</header>
