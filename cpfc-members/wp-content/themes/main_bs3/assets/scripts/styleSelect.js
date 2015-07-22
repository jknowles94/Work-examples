/** Style Selects **/

(function ($) {
    $.fn.extend({
        styleSelects: function (options) {
            return this.each(function () {
                var $this = $(this);
                $this.wrap('<div class="select-wrap"></div>');
                var currentSelected = $this.find(':selected');
                $this.after('<span class="styleSelectWrap"><span class="styleSelect select-text" value="' + currentSelected.val() + '">' + currentSelected.text() + '</span></span>').css({ opacity: 0 }); //(parseInt($this.next().css("width")) + 3)
                var $styleSelectWrap = $this.next(), $styleSelect = $styleSelectWrap.children();

                $this.css({ width: $this.outerWidth() });

                $this.change(function () {
                    $styleSelect.text($this.find(':selected').text()).attr("value", $this.find(':selected').val()).removeClass("select-text");
                });
            });
        }
    });
})(jQuery);