(function (window, $) {
    'use strict';

    function toggleUserBranchField() {
        const $roleSelect = $('#user-role-select');
        const $branchField = $('#user-branch-field');
        const $headOfficeField = $('#user-head-office-field');

        if (!$roleSelect.length) {
            return;
        }

        const isOwner = $roleSelect.val() === 'owner';

        $branchField.toggleClass('hidden', isOwner);
        $headOfficeField.toggleClass('hidden', !isOwner);
    }

    function initUserBranchField() {
        const $roleSelect = $('#user-role-select');

        if (!$roleSelect.length) {
            return;
        }

        $roleSelect.on('change', toggleUserBranchField);
        toggleUserBranchField();
    }

    $(initUserBranchField);
})(window, jQuery);
