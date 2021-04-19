/**
 * Created by Admin on 17.10.2017.
 */
(function ($) {
  Drupal.behaviors.NLSearchBehavior = {
    attach: function (context, settings) {
      $(window).on("load",(function() {
        var ind = 0;
        var tabInd = 0;
        var max = 0;
        var depth = 3;
        var keys = drupalSettings.nls.keys;
        drupalSettings.nls.resultIs = false;
        $('.nl-search-tab-content .view-nl-search').each(function () {
          for(i = 1; i <= depth; i++){
            if($(this).find('.row-counter-' + i + ' .title-wrapper h3 span').text() == keys){
              tabInd = ind;
              drupalSettings.nls.resultIs = true;
              return false;
            }
          }
          var hdr = $(this).find('.view-header').html();
          if(hdr.length) {
            var arr = $.trim(hdr).split(' ');
            if (max < parseInt(arr[0])) {
              max = parseInt(arr[0]);
              tabInd = ind;
              drupalSettings.nls.resultIs = true;
            }
          }
          ind++;
          if(ind >= 2) return false;
        });
        nlTabInit({active: tabInd});
        //$("#stabs").tabs({active: tabInd});
        $('#autocomlpete-sform #edit-keys').val(keys);
      })
      );
      $(document).on("click", '#stabs li.nl-tab-header',function () {
        nlTabLoad($(this));
      });
      $(document).on("click", '#stabs li.stabs-top-header',function () {
        $('.ui-tabs-nav li details').removeClass('selected');
      });
      $('#stabs li details li').click(function (event) {
        var rootDetails = $(this).parents('details').eq(0);
        rootDetails.removeAttr("open");
        rootDetails.addClass('selected');
        rootDetails.find('summary').text($(this).text());
        //nlTabLoad($(this));
      });
    }
  };
  // $(document).on("click", '#stabs',function () {
  //   project_ajax_load();
  // });
  function nlTabInit(options) {
    var obj = $("#stabs");
    obj.addClass('ui-tabs');
    obj.find("ul").addClass('ui-tabs-nav');
    var tabLi = obj.find("ul li");
    //tabLi.addClass('nl-tab-header');
    var activeTab = obj.find("ul li").eq(options.active);
    nlTabLoad(activeTab);
  }
  function nlTabHideAll(obj){
    var tabLi = obj.find("ul li");
    tabLi.each(function () {
      $(this).removeClass('ui-tabs-active ui-state-active');
      $("#"+$(this).attr('aria-controls')).hide();
    });
  }
  function nlTabLoad(obj) {
    var root = $("#stabs");
    nlTabHideAll(root);
    var dest = $('#'+obj.attr('aria-controls'));
    dest.show();
    obj.addClass('ui-tabs-active ui-state-active');
  }
  function project_ajax_load() {
    $.ajax({
      url: '/tabs/ajax/map-container/piois_etc/page_1/59+60+62',
      dataType: 'json',
      success: function (data) {
          $('#map-container').html(data[3].data);
        $('#map-container').append(data[2].data);
      }
    });
   // $('#map-container').load("/tabs/ajax/map-container/piois_etc/page_1/59+60+62");
  }
})(jQuery);
