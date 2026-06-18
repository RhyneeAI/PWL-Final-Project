(function () {
    const DISMISS_MS = 5000;
    const FADE_MS = 500;

    document.querySelectorAll('[data-flash-message]').forEach(function (el) {
        setTimeout(function () {
            el.classList.add('opacity-0', 'max-h-0', 'mb-0', 'py-0', 'border-0', 'overflow-hidden');

            setTimeout(function () {
                el.remove();
            }, FADE_MS);
        }, DISMISS_MS);
    });
})();
