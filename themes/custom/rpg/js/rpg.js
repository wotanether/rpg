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
})(jQuery, this, Drupal, drupalSettings);