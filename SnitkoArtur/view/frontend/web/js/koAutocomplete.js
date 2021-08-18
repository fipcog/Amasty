define(['uiComponent', 'mage/url', 'jquery'], function (Component, urlBuilder, $) {
    return Component.extend({
        defaults: {
            searchText: '',
            searchResult: []
        },
        initObservable: function () {
            this._super();
            this.observe(['searchText', 'searchResult']);
            return this;
        },
        initialize: function () {
            this._super();
            this.searchText.subscribe(this.handleAutocomplete.bind(this));
        },
        handleAutocomplete: function (searchValue) {
            var queryText = searchValue;
            var url = urlBuilder.build('artur/ajax/autocomplete');

            if(queryText.length >= 3) {
                $.getJSON(url, {qText: queryText}, function (data) {
                    this.searchResult(data);
                }.bind(this));
            } else {
                this.searchResult([]);
            }
        }
    });
});
