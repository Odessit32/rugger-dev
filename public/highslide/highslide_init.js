hs.graphicsDir = '/highslide/graphic/';
hs.expandCursor = 'zoomin.cur';
hs.expandCursor = 'zoomout.cur';
hs.align = 'center';
hs.transitions = ['expand', 'crossfade'];
hs.outlineType = 'rounded-white';
hs.fadeInOut = true;
hs.captionEval = 'this.a.title';
hs.padToMinWidth = true;
hs.allowWidthReduction = true;
hs.maxHeight = 600;
//hs.dimmingOpacity = 0.75;

// Add the controlbar
hs.addSlideshow({
//slideshowGroup: 'group1',
    interval: 5000,
    repeat: true,
    useControls: true,
    fixedControls: 'fit',
    overlayOptions: {
        className: 'large-dark',
        opacity: 0.75,
        position: 'bottom center',
        hideOnMouseOut: true
    }
});

// Russian language strings
hs.lang = {
    cssDirection: 'ltr',
    loadingText: 'Загружается...',
    loadingTitle: 'Нажмите для отмены',
    focusTitle: 'Нажмите чтобы поместить на передний план',
    fullExpandTitle: 'Развернуть до оригинального размера',
    creditsText: '',
    creditsTitle: '',
    previousText: 'Предыдущее',
    nextText: 'Следующее',
    moveText: 'Переместить',
    closeText: 'Закрыть',
    closeTitle: 'Закрыть (esc)',
    resizeTitle: 'Изменить размер',
    playText: 'Слайдшоу',
    playTitle: 'Начать слайдшоу (пробел)',
    pauseText: 'Пауза',
    pauseTitle: 'Приостановить слайдшоу (пробел)',
    previousTitle: 'Предыдущее (стрелка влево)',
    nextTitle: 'Следующее (стрелка вправо)',
    moveTitle: 'Переместить',
    fullExpandText: 'Оригинальный размер',
    number: 'Изображение %1 из %2',
    restoreTitle: 'Нажмите чтобы закрыть изображение, нажмите и перетащите для изменения местоположения. Для просмотра изображений используйте стрелки.'
};
