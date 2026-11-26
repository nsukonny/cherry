{* Подвал. Ожидает $categories (список категорий). *}
<footer class="footer">
	<div class="footer__band" aria-hidden="true">
		<span class="footer__wordmark">cherry</span>
	</div>
	<div class="footer__inner">
		<div class="footer__about">
			<span class="footer__brand">cherry</span>
			<p class="footer__text">Блог о сортах вишни: описания, сравнения и советы по уходу.</p>
		</div>
		<nav class="footer__nav" aria-label="Категории в подвале">
			<span class="footer__nav-title">Категории</span>
			{foreach $categories|default:[] as $category}
			<a class="footer__link" href="/{$category.slug}/">{$category.title}</a>
			{/foreach}
		</nav>
		<span class="footer__copy">© {$smarty.now|date_format:'%Y'}</span>
	</div>
</footer>
