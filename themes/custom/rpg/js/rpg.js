/**
 * @file
 * Placeholder file for custom sub-theme behaviors.
 *
 */
(function ($, window, Drupal, drupalSettings) {

    /**
     * Use this behavior as a template for custom Javascript.
     */
    Drupal.behaviors.newGame = {
        attach: function (context, settings) {

            $.fn.modal = function(){
                $('#new-game-confirm').foundation('reveal', 'open');
            };
            var $btn = $('#new-game-confirm-btn');
            $btn.click(function(){
                $('#home-form').submit();
            });
        }
    };
    $.fn.newPlayer = function(){
        $('#new-player').foundation('reveal', 'close');
        $("*").css("pointer-events", "auto");
    };
    $(document).ready(function () {
        $('#new-player').foundation('reveal', 'open');
        $(document).on('opened.fndtn.reveal', '[data-reveal]', function () {
            $("*").css("pointer-events", "none");
            $('#new-player *').css("pointer-events", "auto");
        });
    });

})(jQuery, this, Drupal, drupalSettings);