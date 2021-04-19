  
  var ua = navigator.userAgent.toLowerCase();
  var isOpera = (ua.indexOf('opera')  > -1);
  var isIE = (!isOpera && ua.indexOf('msie') > -1);

  function getDocumentHeight() {
    return Math.max(document.compatMode != 'CSS1Compat' ? document.body.scrollHeight : document.documentElement.scrollHeight, getViewportHeight());
  }

  function getViewportHeight() {
    return ((document.compatMode || isIE) && !isOpera) ? (document.compatMode == 'CSS1Compat') ? document.documentElement.clientHeight : document.body.clientHeight : (document.parentWindow || document.defaultView).innerHeight;
  }
  
  function getXmlHttp(){
    var xmlhttp;
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlhttp = false;
      }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
      xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
  }
  
  function PopUpShow(){
    jQuery("#videopopup_wrapper").show();
    PopUpPosition();
    FB.XFBML.parse();
    VK.init({apiId: 3093655, onlyWidgets: true});
  }
  function PopUpHide(){
    jQuery("#videopopup_wrapper").hide();
    jQuery('#item_body').html('');
    window.history.pushState(null, null, "http://www.interviewrussia.ru/intervideo");
  }
  function PopUpReq(href){
    var xmlhttp = getXmlHttp()
    parsedURL = href.split('/');
    nid = parsedURL[parsedURL.length-1];
    xmlhttp.open('GET', '../videoitems/get/item/'+nid, true);
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4) {
        if(xmlhttp.status == 200) {
          result = JSON.parse(xmlhttp.responseText);
          jQuery('#item_body').html(result.body);
        }
      }
    };
    xmlhttp.send(null);
    PopUpShow();
  }
  
  
  function PopUpPosition(){
    jQuery("#videopopup_wrapper").height(jQuery("body").height());
    var sctop = document.documentElement.scrollTop;
        scleft = document.documentElement.scrollLeft;
        w = jQuery(window);
        sleft = (w.width()-jQuery("#videopopup").width())/2+w.scrollLeft();
        stop = (w.height()-jQuery("#videopopup").height())/2+w.scrollTop();
        //alert(sleft);
    if(w.height() >= jQuery("#videopopup").height()){
      jQuery("#videopopup").offset({top:stop,left:sleft});
    }
    else{
      jQuery("#videopopup").offset({top:w.scrollTop()+30,left:sleft});
    }
  }
  
  function supports_history_api() {
    return !!(window.history && window.history.pushState);
  }
  
  window.onload = function() {
    if (!supports_history_api()) { return; }
    window.setTimeout(function() {
      window.addEventListener("popstate", function(e) {
        PopUpReq(location.pathname);
      }, false);
    }, 1);
  }