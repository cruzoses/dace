(function($) {
    var Inactividad = {
        config: {
            timeout: 300000,
            countdown: 60,
            keepaliveInterval: 120000
        },
        timer: null,
        countdownTimer: null,
        keepaliveTimer: null,
        countdownValue: 60,
        warningVisible: false,

        init: function(options) {
            $.extend(this.config, options);
            this.countdownValue = this.config.countdown;
            this.setupActivityListeners();
            this.startKeepalive();
            this.resetTimer();
        },

        setupActivityListeners: function() {
            var self = this;
            $(document).on('mousemove', function() {
                if (!self.warningVisible) {
                    self.resetTimer();
                }
            });
            $(document).on('keydown click scroll touchstart', function() {
                if (self.warningVisible) {
                    self.cancel();
                } else {
                    self.resetTimer();
                }
            });
        },

        resetTimer: function() {
            clearTimeout(this.timer);
            this.timer = setTimeout($.proxy(this.showWarning, this), this.config.timeout);
        },

        showWarning: function() {
            if (this.warningVisible) return;
            this.warningVisible = true;
            this.countdownValue = this.config.countdown;
            this.updateDisplay();
            $('#session-countdown').fadeIn(300);
            var self = this;
            this.countdownTimer = setInterval(function() {
                self.countdownValue--;
                self.updateDisplay();
                if (self.countdownValue <= 0) {
                    self.expire();
                }
            }, 1000);
        },

        hideWarning: function() {
            this.warningVisible = false;
            clearInterval(this.countdownTimer);
            $('#session-countdown').fadeOut(200);
        },

        updateDisplay: function() {
            $('#session-countdown .countdown-seconds').text(this.countdownValue);
        },

        cancel: function() {
            this.hideWarning();
            this.resetTimer();
        },

        expire: function() {
            clearInterval(this.countdownTimer);
            clearInterval(this.keepaliveTimer);
            window.location.href = basePath + 'logout';
        },

        startKeepalive: function() {
            var self = this;
            this.keepaliveTimer = setInterval(function() {
                $.ajax({
                    url: basePath + 'keepalive',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'expired') {
                            self.expire();
                        }
                    }
                });
            }, this.config.keepaliveInterval);
        }
    };

    window.Inactividad = Inactividad;
})(jQuery);
