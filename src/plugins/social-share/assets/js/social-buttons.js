/**
 * ソーシャルボタン
 */
(function ($) {
    "use strict";

    var defaultOptions = {
        wrapSelector: ".js-sharebutton",
        dataSelector: "service-target",
        dataUrlSelector: "shareurl",
        itemSelector: 'a[data-service-target]'
    }


    var SocialButtons = function (options) {
        this.options = $.extend(defaultOptions, options);
        this.callback = {};
        this.counter = ["twitter", "facebook", "googleplus", "feedly"]
    }

    /**
     * 初期化
     */
    SocialButtons.prototype.init = function () {
        this.wrap = $(this.options.wrapSelector);
        var items = this.wrap.find(this.options.itemSelector);
        this.items = {};
        for (var i = 0; i < items.length; i++) {
            var serviceName = $(items[i]).data(this.options.dataSelector);
            if (typeof this.items[serviceName] === "undefined") {
                this.items[serviceName] = [];
            }
            this.items[serviceName].push(items[i]);
        }

        this.setServiceCount();
        this.run();
        this.open();
    }

    /**
     * 実行
     */
    SocialButtons.prototype.run = function () {

        // 取得したボタンをループ
        for (var serviceName in this.items) {

            // 各ボタンごとに実行する
            var buttons = this.items[serviceName];
            for (var i = 0; i < buttons.length; i++) {
                var button = $(buttons[i]);

                // ボタンごとにコールバックが登録されていたら実行
                if (typeof this.callbacks[serviceName] === "object"
                    && typeof this.callbacks[serviceName].getCount === "function") {
                    this.callbacks[serviceName].getCount(button.data(this.options.dataUrlSelector));
                }
            }
        }
    }

    /**
     * 各種サービスの取得先を登録
     */
    SocialButtons.prototype.setServiceCount = function () {
        var self = this;
        this.callbacks = {
            // Twitter
            "twitter": {
                getCount: function (permalink) {
                    $.getJSON('http://jsoon.digitiminimi.com/twitter/count.json?url=' + permalink + '&callback=?')
                        .done(function (data) {
                                if (data.count > 0) {
                                    self.counter.twitter = data.count;
                                    self.updateCount("twitter");
                                }
                            }
                        );
                }
            },

            // Facebook
            "facebook": {
                getCount: function (permalink) {
                    $.getJSON('https://graph.facebook.com/?id=' + permalink + '&callback=?')
                        .done(function (data) {

                            if (data.shares) {
                                self.counter.facebook = data.shares;
                                self.updateCount("facebook");
                            } else {

                            }
                        })
                        .fail(function (data) {
                        });
                }
            },

            // Google Plus
            "googleplus": {
                getCount: function (permalink) {
                    $.ajax({
                        type: "get", dataType: "xml",
                        url: "http://query.yahooapis.com/v1/public/yql",
                        data: {
                            q: "SELECT content FROM data.headers WHERE url='https://plusone.google.com/_/+1/fastbutton?hl=ja&url=" + permalink + "' and ua='#Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36'",
                            format: "xml",
                            env: "http://datatables.org/alltables.env"
                        },
                        success: function (data) {
                            var content = $(data).find("content").text();
                            var match = content.match(/window\.__SSR[\s*]=[\s*]{c:[\s*](\d+)/i);
                            var count = (match != null) ? match[1] : 0;
                            self.counter.googleplus = count;
                            self.updateCount("googleplus");
                        }
                    });
                }
            },

            // hatena bookmark
            "hatenabookmark": {
                getCount: function (permalink) {
                    $.getJSON('http://api.b.st-hatena.com/entry.count?url=' + encodeURIComponent(permalink) + '&callback=?')
                        .done(function (data) {
                            if (typeof data !== "undefined") {
                                self.counter.hatenabookmark = data;
                                self.updateCount("hatenabookmark");
                            }
                        })
                        .fail(function (data) {
                        });
                }
            },
            "feedly": {
                getCount: function (permalink) {
                }
            }
        }
    }

    /**
     * クリックした時の動作
     */
    SocialButtons.prototype.open = function () {
        var width = "600";
        var height = "400";
        var left = (window.innerWidth / 2) - (width / 2);
        var top = (window.innerHeight / 2) - (height / 2);

        this.wrap.find(this.options.itemSelector).on('click', function (e) {
            e.preventDefault();
            var shareUrl = $(this).attr("href");
            window.open(shareUrl, "", 'top=' + top + ',left=' + left + ',width=' + width + ', height=' + height + ', menubar=no, toolbar=no, scrollbars=yes');
        });
    }

    /**
     * カウントを更新する
     */
    SocialButtons.prototype.updateCount = function (service) {

        var button = $(this.items[service]);
        var now = 0;
        var maxCount = this.counter[service];

        var span = button.find("span");

        function update(){

            if ( maxCount <= now ){
                return false;
            }
            setTimeout(function(){
                now = now+1;
                var new_span = $("<b></b>");
                span.html(new_span.append(now));
                update();
            },5);
        }
        update();


    }

    function init() {
        var b = new SocialButtons();
        
        b.init();
    }

    addEventListener("DOMContentLoaded", init);

})(jQuery);
