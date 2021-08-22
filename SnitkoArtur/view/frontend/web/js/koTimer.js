define(['uiComponent', 'jquery'], function (Component, $) {
    return Component.extend({
        defaults: {
            timerId: null,
            hours: "00",
            minutes: "00",
            seconds: "00"
        },
        initObservable: function () {
            this._super();
            this.observe(['hours', 'minutes', 'seconds']);
            return this;
        },
        initialize: function () {
            this._super();
        },
        timerCounter: function () {
            var second = Number(this.seconds());
            var minute = Number(this.minutes());
            var hour = Number(this.hours());

            if (second === 60) {
                this.minutes(this.formatNumber((minute + 1)));
                this.seconds(this.formatNumber(0));
            } else {
                this.seconds(this.formatNumber((second + 1)));
            }
            if (minute === 60) {
                this.hours(this.formatNumber((hour + 1)));
                this.minutes(this.formatNumber(0));
            }
            if (hour === 24) this.stopTimer();
        },
        startTimer: function () {
            clearInterval(this.timerId);
            var timerId = setInterval(this.timerCounter.bind(this), 1000);
            this.timerId = timerId;
        },
        pauseTimer: function () {
            clearInterval(this.timerId);
        },
        stopTimer: function () {
            clearInterval(this.timerId);
            this.hours("00");
            this.minutes("00");
            this.seconds("00");
        },
        formatNumber: function (Number) {
            return (Number).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false});
        }
    })
})
