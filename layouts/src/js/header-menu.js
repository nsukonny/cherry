/**
 * Открывает и закрывает меню категорий на телефоне.
 * На десктопе бургер скрыт стилями, меню видно всегда.
 */
export function initHeaderMenu() {
	const header = document.querySelector('.header');
	const burger = header?.querySelector('.header__burger');

	if (!header || !burger) {
		return;
	}

	const setOpen = (open) => {
		header.classList.toggle('header--open', open);
		burger.setAttribute('aria-expanded', String(open));
		burger.setAttribute('aria-label', open ? 'Закрыть меню' : 'Открыть меню');
	};

	burger.addEventListener('click', () => {
		setOpen(!header.classList.contains('header--open'));
	});

	document.addEventListener('keydown', (event) => {
		if (event.key === 'Escape' && header.classList.contains('header--open')) {
			setOpen(false);
			burger.focus();
		}
	});
}
