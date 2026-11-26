<nav class="pagination" aria-label="Страницы">
    <ul class="pagination__list">
        <li>
            <span class="pagination__link pagination__link--disabled" aria-hidden="true">
                {include file="partials/icons/chevron-left.tpl" class="pagination__icon"}
            </span>
        </li>
        <li><a class="pagination__link pagination__link--current" href="category.html?page=1"
               aria-current="page">1</a></li>
        <li><a class="pagination__link" href="category.html?page=2">2</a></li>
        <li><a class="pagination__link" href="category.html?page=3">3</a></li>
        <li>
            <a class="pagination__link" href="category.html?page=2" aria-label="Следующая страница">
                {include file="partials/icons/chevron-right.tpl" class="pagination__icon"}
            </a>
        </li>
    </ul>
</nav>
