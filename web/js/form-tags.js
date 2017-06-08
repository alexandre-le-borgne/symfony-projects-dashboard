(function ($) {
    $.fn.formTags = function (source) {
        this.on('tokenfield:removedtoken', function (e) {
            source.push(e.attrs.value);
            $(this).data('bs.tokenfield').$input.autocomplete({source: source});
        }).on('tokenfield:initialize', function (e) {
            $(this).data('bs.tokenfield').$input.autocomplete({
                select: function (e, ui) {
                    source = jQuery.grep(source, function (element) {
                        return element !== ui.item.value;
                    });

                    $(this).autocomplete('option', 'source', source);

                    var self = $(this);
                    setTimeout(function () {
                        self.autocomplete("search");
                    }, 100);
                }
            });
        }).tokenfield({
            autocomplete: {
                source: source,
                delay: 100,
                minLength: 0
            },
            showAutocompleteOnFocus: true
        });
        return this;
    };
}(jQuery));