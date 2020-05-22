$(document).ready(function () {
    'use strict';
    var $searchInput = $('.search-bar input');
    var $searchCloseBtn = $('.search-close');

    // Reusable utilities
    window.gullUtils = {
        isMobile: function isMobile() {
            return window && window.matchMedia('(max-width: 767px)').matches;
        },
        changeCssLink: function (storageKey, fileUrl) {
            localStorage.setItem(storageKey, fileUrl);
            location.reload();
        }
    }

    // Search toggle
    var $searchUI = $('.search-ui');
    $searchInput.on('focus', function () {
        $searchUI.addClass('open');
    });
    $searchCloseBtn.on('click', function () {
        $searchUI.removeClass('open');
    });

    // Perfect scrollbar
    $('.perfect-scrollbar, [data-perfect-scrollbar]').each(function (index) {
        var $el = $(this);
        var ps = new PerfectScrollbar(this, {
            suppressScrollX: $el.data('suppress-scroll-x'),
            suppressScrollY: $el.data('suppress-scroll-y')
        });
    });

    // Full screen
    function cancelFullScreen(el) {
        var requestMethod = el.cancelFullScreen || el.webkitCancelFullScreen || el.mozCancelFullScreen || el.exitFullscreen;
        if (requestMethod) { // cancel full screen.
            requestMethod.call(el);
        }
        else if (typeof window.ActiveXObject !== 'undefined') { // Older IE.
            var wscript = new ActiveXObject('WScript.Shell');
            if (wscript !== null) {
                wscript.SendKeys('{F11}');
            }
        }
    }

    function requestFullScreen(el) {
        // Supports most browsers and their versions.
        var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullscreen;

        if (requestMethod) { // Native full screen.
            requestMethod.call(el);
        }
        else if (typeof window.ActiveXObject !== 'undefined') { // Older IE.
            var wscript = new ActiveXObject('WScript.Shell');
            if (wscript !== null) {
                wscript.SendKeys('{F11}');
            }
        }
        return false
    }

    function toggleFullscreen() {
        var elem = document.body;
        var isInFullScreen = (document.fullScreenElement && document.fullScreenElement !== null) || (document.mozFullScreen || document.webkitIsFullScreen);

        if (isInFullScreen) {
            cancelFullScreen(document);
        }
        else {
            requestFullScreen(elem);
        }
        return false;
    }

    $('[data-fullscreen]').on('click', toggleFullscreen);

    const btnConfirm = document.querySelectorAll('[data-jvista="confirm"]');
    const confirmAction = (event) => {
        event.preventDefault();
        const target = event.currentTarget;
        bootbox.confirm({
            message: target.dataset.msj,
            buttons: {
                confirm: {
                    label: 'Si',
                    className: 'btn btn-danger'
                },
                cancel: {
                    label: 'No',
                    className: 'btn btn-default'
                }
            },
            callback: (result) => {
                if (result) window.location.href = target.href;
            }
        });
    };
    btnConfirm.forEach(btn => btn.addEventListener('click', confirmAction));

});