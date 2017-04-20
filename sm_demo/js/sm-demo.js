/**
 * @file
 * Drupal test selectmenu attach js.
 */

Drupal.behaviors.smdemo = {
  attach: function (context, settings) {
    (function ($) {
      // $('select', context).each(function (context) {
      //   $(this).addClass('sm-processed');
      //   $(this).selectmenu();
      // });
      //$('select').selectmenu().addClass('sm-processed');
    })(jQuery);
  }
};

(function ($) {
  $(document).ready(function($){
    $('select').selectmenu().addClass('sm-processed');
  });
})(jQuery);
