if (typeof dvizh == "undefined" || !dvizh) {
    var dvizh = {};
}

dvizh.filterAjax = {
    rangeDelay: false,
    init: function() {
        $(document).on('change', '.dvizh-filter select, .dvizh-filter input[type=checkbox], .dvizh-filter input[type=radio]', this.renderResults);
        $(document).on('change', '.dvizh-filter input[type=text]', function() {
            if(dvizh.filterAjax.rangeDelay) {
                clearTimeout(dvizh.filterAjax.rangeDelay);
            }
            
            dvizh.filterAjax.rangeDelay = setTimeout(function() {
                dvizh.filterAjax.renderResults();
            }, 800);
        });
    },
    renderResults: function() {
        var data = $('.dvizh-filter').serialize();
        var resultHtmlSelector = $('.dvizh-filter').data('resulthtmlselector');

        $(resultHtmlSelector).css('opacity', 0.3);

        $(resultHtmlSelector).load(location.protocol + '//' + location.host + location.pathname+'?'+data+' '+resultHtmlSelector, function() {
            $(resultHtmlSelector).css('opacity', 1);
        });
        
        return false;
    }
};

dvizh.filterAjax.init();
