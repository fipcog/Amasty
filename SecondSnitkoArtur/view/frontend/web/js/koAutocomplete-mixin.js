define(['uiComponent', 'mage/url', 'jquery'], function (Component, urlBuilder, $) {
    var mixin = {
        handleAutocomplete: function (searchValue) {
            var queryText = searchValue;
            var url = urlBuilder.build('secondartur/ajax/autocomplete');

            if(queryText.length >= 5) {
                $.getJSON(url, {qText: queryText}, function (data) {
                    this.searchResult(data);
                    console.log(this.searchResult);
                }.bind(this));
            } else {
                this.searchResult([]);
            }
        }
    }

    return function (target) {
        return target.extend(mixin);
    }
})
