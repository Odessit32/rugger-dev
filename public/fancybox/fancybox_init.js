/**
 * Fancybox initialization - replacement for Highslide
 */
(function() {
    // Initialize Fancybox with Russian language settings
    Fancybox.defaults.l10n = {
        CLOSE: "Закрыть",
        NEXT: "Следующее",
        PREV: "Предыдущее",
        MODAL: "Вы можете закрыть это окно, нажав ESC",
        ERROR: "Что-то пошло не так. Пожалуйста, попробуйте позже",
        IMAGE_ERROR: "Изображение не найдено",
        ELEMENT_NOT_FOUND: "HTML элемент не найден",
        AJAX_NOT_FOUND: "Ошибка загрузки AJAX: не найдено",
        AJAX_FORBIDDEN: "Ошибка загрузки AJAX: доступ запрещён",
        IFRAME_ERROR: "Ошибка загрузки страницы",
        TOGGLE_ZOOM: "Изменить масштаб",
        TOGGLE_THUMBS: "Миниатюры",
        TOGGLE_SLIDESHOW: "Слайдшоу",
        TOGGLE_FULLSCREEN: "Во весь экран",
        DOWNLOAD: "Скачать"
    };

    // Default options
    Fancybox.defaults.animated = true;
    Fancybox.defaults.showClass = "f-zoomInUp";
    Fancybox.defaults.hideClass = "f-fadeOut";
    Fancybox.defaults.Toolbar = {
        display: {
            left: ["infobar"],
            middle: [],
            right: ["slideshow", "thumbs", "close"]
        }
    };
    Fancybox.defaults.Images = {
        zoom: true,
        protected: false
    };

    // Bind to elements with onclick="return hs.expand(this)" (legacy Highslide)
    // Convert them to work with Fancybox
    document.addEventListener('DOMContentLoaded', function() {
        // Find all elements with onclick containing hs.expand
        var hsElements = document.querySelectorAll('[onclick*="hs.expand"]');
        hsElements.forEach(function(el) {
            el.removeAttribute('onclick');
            el.setAttribute('data-fancybox', 'gallery');
            if (el.title) {
                el.setAttribute('data-caption', el.title);
            }
        });

        // Bind Fancybox to gallery links
        Fancybox.bind('[data-fancybox]', {
            groupAll: true
        });
    });
})();
