(function (window, $) {
    'use strict';

    let menuItems = [];
    let activeIndex = -1;

    function normalize(text) {
        return String(text || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }

    function collectMenuItems() {
        const items = [];
        let currentSection = 'Utama';

        $('#sidebar nav').children().each(function () {
            const $el = $(this);

            if ($el.hasClass('sidebar-section-label')) {
                currentSection = $el.text().trim();
                return;
            }

            if (!$el.hasClass('admin-menu-item')) {
                return;
            }

            const href = $el.attr('href');
            if (!href || href === '#') {
                return;
            }

            items.push({
                label: $el.find('.menu-text').text().trim() || $el.attr('title') || '',
                href,
                icon: ($el.find('i').attr('class') || 'fas fa-link').trim(),
                section: currentSection,
                title: $el.attr('title') || '',
            });
        });

        return items;
    }

    function filterMenuItems(query) {
        const normalizedQuery = normalize(query);

        if (!normalizedQuery) {
            return menuItems;
        }

        return menuItems.filter(function (item) {
            const haystack = normalize([item.label, item.section, item.title].join(' '));
            return haystack.includes(normalizedQuery);
        });
    }

    function hideDropdown() {
        $('#navbar-search-dropdown').addClass('hidden');
        activeIndex = -1;
    }

    function showDropdown() {
        $('#navbar-search-dropdown').removeClass('hidden');
        $('#notification-dropdown, #user-dropdown').addClass('hidden');
    }

    function renderResults(results) {
        const $results = $('#navbar-search-results');
        const $empty = $('#navbar-search-empty');

        $results.empty();
        activeIndex = -1;

        if (!results.length) {
            $empty.removeClass('hidden');
            return;
        }

        $empty.addClass('hidden');

        results.forEach(function (item, index) {
            const $button = $('<button type="button" class="navbar-search-item w-full text-left"></button>');
            $button.attr('data-index', index);
            $button.attr('data-href', item.href);
            $button.html(
                '<span class="navbar-search-item-icon"><i class="' + item.icon + '"></i></span>' +
                '<span class="navbar-search-item-body">' +
                    '<span class="navbar-search-item-label">' + escapeHtml(item.label) + '</span>' +
                    '<span class="navbar-search-item-section">' + escapeHtml(item.section) + '</span>' +
                '</span>' +
                '<span class="navbar-search-item-arrow"><i class="fas fa-arrow-right"></i></span>'
            );
            $results.append($button);
        });
    }

    function escapeHtml(text) {
        return $('<div>').text(text).html();
    }

    function setActiveIndex(index) {
        const $items = $('#navbar-search-results .navbar-search-item');
        $items.removeClass('is-active');
        activeIndex = index;

        if (index >= 0 && index < $items.length) {
            $items.eq(index).addClass('is-active')[0].scrollIntoView({ block: 'nearest' });
        }
    }

    function navigateTo(href) {
        if (href) {
            window.location.href = href;
        }
    }

    function runSearch() {
        const query = $('#search-input').val().trim();
        const results = filterMenuItems(query);
        renderResults(results);
        showDropdown();
    }

    function initNavbarSearch() {
        const $input = $('#search-input');
        const $wrap = $('#navbar-search-wrap');

        if (!$input.length) {
            return;
        }

        menuItems = collectMenuItems();

        $input.on('focus', function () {
            runSearch();
        });

        $input.on('input', function () {
            runSearch();
        });

        $input.on('keydown', function (e) {
            const $items = $('#navbar-search-results .navbar-search-item');
            const count = $items.length;

            if (e.key === 'Escape') {
                hideDropdown();
                $input.blur();
                return;
            }

            if (!$('#navbar-search-dropdown').is(':visible') || !count) {
                return;
            }

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                setActiveIndex(activeIndex < count - 1 ? activeIndex + 1 : 0);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                setActiveIndex(activeIndex > 0 ? activeIndex - 1 : count - 1);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                const target = activeIndex >= 0 ? $items.eq(activeIndex) : $items.first();
                navigateTo(target.data('href'));
            }
        });

        $('#navbar-search-results').on('click', '.navbar-search-item', function () {
            navigateTo($(this).data('href'));
        });

        $('#navbar-search-results').on('mouseenter', '.navbar-search-item', function () {
            setActiveIndex(Number($(this).data('index')));
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('#navbar-search-wrap').length) {
                hideDropdown();
            }
        });

        $(document).on('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                $input.trigger('focus');
                runSearch();
            }
        });
    }

    window.initNavbarSearch = initNavbarSearch;
})(window, jQuery);
