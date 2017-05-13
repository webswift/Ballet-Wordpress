/*jslint browser: true*/
/*global $, jQuery*/

var qualifyURL = function (url) {
    var img = document.createElement('img');
    img.src = url; // set string url
    url = img.src; // get qualified url
    img.src = null; // no server request
    return url;
};

( function( $ ) {

  function displayWarning(options, jsonAPI, customMessage){
    var divMain = $(options.main_div);
    var message = "";
    console.log('ERROR: ', jsonAPI)
    if(jsonAPI.meta.error_message === "no search query provided"){
       message = "Hey, you forgot to fill Username/Hashtag!"
    }else{
      message = jsonAPI.meta.error_message;
    }
    if(customMessage)
      divMain.append('Instagram Portfolio says: ' + customMessage);
    else
      divMain.append('Instagram Portfolio says: ' + message);
  }

  function removeEmoji(caption) {
    var withoutEmoji = caption.replace(/\ud83d[\ude00-\ude4f]/g, '');
    return withoutEmoji;
  }

  function justifyGallery(options){

    var div_photos = options.main_div + ' .seg-instaportfolio-photos';

    // lets justify the gallery
    $(div_photos).justifiedGallery({
                          captions : false,
                          lastRow : options.last_row,
                          margins : (options.padding ? options.padding : 0),
                          fixedHeight: (options.fixed_height==='on' ? true : false),
                          rowHeight: options.height,
                          maxRowHeight: options.height
                      });
    
    // $(window).scroll(function() {
    //   if($(window).scrollTop() + $(window).height() == $(document).height()) {
    //     for (var i = 0; i < 5; i++) {
    //         $(div_photos).append('<a>' +
    //             '<img src="http://path/to/image" />' + 
    //             '</a>');
    //     }
    //     $(div_photos).justifiedGallery('norewind');
    // }
   //});

  }

  function lightboxGallery(options, destroy){
    var $element = $(options.id_main_div + " #" + options.id_main_div_photos);
    if(destroy)
      $element.data('lightGallery').destroy(true);
      
    $element.lightGallery({
      videoMaxWidth:'640px',
      thumbnail:true,
      selector: ".seg-instaportfolio-photo"
    });
  }

  function commaSeparateNumber(val) {
    while (/(\d+)(\d{3})/.test(val.toString())) {
      val = val.toString().replace(/(\d+)(\d{3})/, '$1' + '.' + '$2');
    }
    return val;
  }

  function isNumeric(num){
      return !isNaN(num);
  }

  function cssOptions(options) {
    var main_div = options.main_div,
        main_div_photos = options.main_div + " .seg-instaportfolio-photos",
        main_div_photo = options.main_div + " .seg-instaportfolio-photos .seg-instaportfolio-photo",
        main_div_photo_inside = options.main_div + " .seg-instaportfolio-photos .seg-instaportfolio-photo .seg-instaportfolio-inside",
        main_div_photo_inside_img = options.main_div + " .seg-instaportfolio-photos .seg-instaportfolio-photo .seg-instaportfolio-inside img",
        addCSS = "";

    if (options.custom_css) {
      addCSS = options.custom_css;
    }

    // width
    addCSS += main_div + '{ width: '+options.width+' !important; } ';

    //if (options.photo_shape === "Circle")
    //  ulMain_ul_li_img.addClass("seg-circle");

    $(main_div_photo_inside_img).addClass(options.photo_effect);

    ///// ANIMATED LINES ###
    if (options.display_animated_lines === "on") {

      $(main_div_photos).addClass("animated-lines");

      if (options.animated_lines_colour) {
        var iniCSS = " " + options.main_div + " .seg-instaportfolio-photos." + options.hover_effect +  " .seg-instaportfolio-photo .seg-instaportfolio-inside ";
        var colourAnimeLine = options.animated_lines_colour;
        if( options.hover_effect === "seg-flinders" || options.hover_effect === "seg-chapel" ){
          addCSS += iniCSS + ".seg-instaportfolio-captions::before{ border-top: 1px solid " + colourAnimeLine +  "; border-bottom: 1px solid " + colourAnimeLine + "; }";
          addCSS += iniCSS + ".seg-instaportfolio-captions::after{ border-left: 1px solid " + colourAnimeLine +  "; border-right: 1px solid " + colourAnimeLine + "; }";
        }
        if(options.hover_effect === "seg-clayton" || options.hover_effect === "seg-bells"){
          addCSS += iniCSS + " .seg-icon-instagram::after { background:" + colourAnimeLine + " } ";
        }
        if( options.hover_effect === "seg-swanston"){
          addCSS += iniCSS + ".seg-instaportfolio-captions::before{background: " + colourAnimeLine +  "; }";
          addCSS += iniCSS + ".seg-instaportfolio-captions::after{ background: " + colourAnimeLine +  "; }";
        }
        if( options.hover_effect === "seg-caulfield" ){
          addCSS += iniCSS + ".seg-instaportfolio-captions::after{ border: 7px solid " + colourAnimeLine +  "; }";
        }
        if( options.hover_effect === "seg-st-kilda" || options.hover_effect === "seg-angkor" ){
          addCSS += iniCSS + ".seg-instaportfolio-captions .seg-insta-description{ border: 1px solid " + colourAnimeLine +  "; }";
        }
        if( options.hover_effect === "seg-tamachi" || options.hover_effect === "seg-shibuya" ){
          addCSS += iniCSS + ".seg-instaportfolio-captions::before{ border: 1px solid " + colourAnimeLine +  "; }";
        }
        if( options.hover_effect === "seg-asakusa" ){
          addCSS += iniCSS + ".seg-instaportfolio-captions .seg-insta-description{ border-right: 1px solid " + colourAnimeLine +  "; }";
        }
        if( options.hover_effect === "seg-shinjuku" || options.hover_effect === "seg-cinquentenario" || options.hover_effect === "seg-saigon" ){
          addCSS += iniCSS + ".seg-instaportfolio-captions::before{ border: 2px solid " + colourAnimeLine +  "; }";
        }
        if( options.hover_effect === "seg-yoyogi" ){
          addCSS += iniCSS + ".seg-instaportfolio-captions .seg-insta-description{ border-right: 4px solid " + colourAnimeLine +  "; }";
        }
        if( options.hover_effect === "seg-halong" ){
          addCSS += iniCSS + ".seg-instaportfolio-captions .seg-icon-instagram{ border: 2px solid " + colourAnimeLine +  "; }";
          addCSS += iniCSS + ".seg-instaportfolio-captions .seg-icon-like{ border: 2px solid " + colourAnimeLine +  "; }";
        }
        if( options.hover_effect === "seg-chiang" ){
          addCSS += iniCSS + ".seg-instaportfolio-captions::after{ border-top: 1px solid " + colourAnimeLine +  "; border-bottom: 1px solid " + colourAnimeLine +  "; }";
        }
      }
    }

    //hover background
    //addCSS += main_div_photo + ':hover{ background:'+ options.hover_background +'; } ';

    //addCSS += main_div_photo + ':hover .seg-main-image{ opacity:'+ options.hover_opacity +' !important; } ';

    // hover background
    if(options.hover_background && options.hover_background2){
      addCSS += ' ' + main_div_photo + ':hover{ background: ';
      addCSS += ' background: '+options.hover_background+';';
      addCSS += ' background: -moz-linear-gradient(top, '+options.hover_background+' 0%, '+options.hover_background2+' 100%);';
      addCSS += ' background: -webkit-gradient(left top, left bottom, color-stop(0%, '+options.hover_background+'), color-stop(100%, '+options.hover_background2+'));';
      addCSS += ' background: -webkit-linear-gradient(top, '+options.hover_background+' 0%, '+options.hover_background2+' 100%);';
      addCSS += ' background: -o-linear-gradient(top, '+options.hover_background+' 0%, '+options.hover_background2+' 100%);';
      addCSS += ' background: -ms-linear-gradient(top, '+options.hover_background+' 0%, '+options.hover_background2+' 100%);';
      addCSS += ' background: linear-gradient(to bottom, '+options.hover_background+' 0%, '+options.hover_background2+' 100%);';
      addCSS += ' filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="'+options.hover_background+'", endColorstr="'+options.hover_background2+'", GradientType=0 );';
      addCSS += ' } ';
      addCSS += ' ' + main_div_photo + ':hover img{ opacity: ' + options.hover_opacity + '; } ';
    }else{
      if (options.hover_background){
          addCSS += ' ' + main_div_photo + ':hover{ background:' + options.hover_background + ' !important; } ';
          addCSS += ' ' + main_div_photo + ':hover img{ opacity: ' + options.hover_opacity + ' !important; } ';
      }else{
        addCSS += ' ' + main_div_photo + ':hover{ background: black !important; } ';
        addCSS += ' ' + main_div_photo + ':hover img{ opacity: .' + options.hover_opacity + ' !important; } ';
      }
    }

    // photo background
    if(options.photo_background && options.photo_background2){
      addCSS += ' ' + main_div_photo + '{ background: ';
      addCSS += ' background: '+options.photo_background+';';
      addCSS += ' background: -moz-linear-gradient(top, '+options.photo_background+' 0%, '+options.photo_background2+' 100%);';
      addCSS += ' background: -webkit-gradient(left top, left bottom, color-stop(0%, '+options.photo_background+'), color-stop(100%, '+options.photo_background2+'));';
      addCSS += ' background: -webkit-linear-gradient(top, '+options.photo_background+' 0%, '+options.photo_background2+' 100%);';
      addCSS += ' background: -o-linear-gradient(top, '+options.photo_background+' 0%, '+options.photo_background2+' 100%);';
      addCSS += ' background: -ms-linear-gradient(top, '+options.photo_background+' 0%, '+options.photo_background2+' 100%);';
      addCSS += ' background: linear-gradient(to bottom, '+options.photo_background+' 0%, '+options.photo_background2+' 100%);';
      addCSS += ' filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="'+options.photo_background+'", endColorstr="'+options.photo_background2+'", GradientType=0 );';
      addCSS += ' } ';
      addCSS += ' ' + main_div_photo + ' img{ opacity: ' + options.photo_opacity + '; } ';
    }else{
      if (options.photo_background){
          addCSS += ' ' + main_div_photo + '{ background:' + options.photo_background + ' !important; } ';
          addCSS += ' ' + main_div_photo + ' img{ opacity: ' + options.photo_opacity + ' !important; } ';
      }
    }

    if(addCSS)
      $('head').append('<style>' + addCSS + '</style>');
  }

  function scrollEffect(options) {
    var scroll_delay = 100;
    if (options.scroll_delay !== "")
      scroll_delay = parseInt(options.scroll_delay, 10);

    var liBoxElement = jQuery(options.main_div + ' .seg-instaportfolio-photos');
    var liBoxElementFirst = jQuery(options.main_div + ' .seg-instaportfolio-photos .seg-instaportfolio-photo:first-child');
    var animation = '';
    var delay = 0;
    var i = 0;
    var effects = ["fade", "scale"];


    for (i = 0; i < effects.length; i++) {
      if (liBoxElementFirst.hasClass("seg-scroll-" + effects[i])) {
        animation = effects[i];
      }
    }

    //// WHEN SCROLL LET'S TRIGGER SCROLL EFFECTS
    if (animation) {
      jQuery(options.main_div).appear();
      //// IF IS ALREADY APPEARED WHEN LOAD THE PAGE JUST TRIGGER THE EFFECTS, ELSE WAIT UNTIL SCROLL TO 
      if (jQuery(options.main_div).is(':appeared')) {
        liBoxElement.find(".seg-instaportfolio-photo").each(function () {
          var li = jQuery(this);
          setTimeout(function () {
            li.addClass(animation).addClass('seg-scroll-' + animation + '-animated');
          }, delay += scroll_delay);
        });
      } else {
        jQuery(document.body).on('appear', options.main_div, function () {
          liBoxElement.find(".seg-instaportfolio-photo").each(function () {
            var li = jQuery(this);
            setTimeout(function () {
              li.addClass(animation).addClass('seg-scroll-' + animation + '-animated');
            }, delay += scroll_delay);
          });
        });
      }
    } else {
      liBoxElement.css({
        "opacity": "1"
      });
    }
  }

  function filterInstagram(data, options){
    var new_data = [];
    for (var i = 0; i < data.length ; i++) {
      if(options.filter_users.indexOf(data[i].user.username) > -1){
        console.log(data[i].user.username);
      }else{
        new_data.push(data[i]);
      }
    }
    return new_data;
  }

  function appendHTML(options, jsonAPI, htmlHeader) {
    var  num_photos = 0,
         mainDiv = $(options.main_div),
         html = "",
         html_li = "",
         radix = 10,
         textCaption = "",
         data = [],
         i = 0, 
         j = 0,
         lightboxContent = "",
         data_src = "",
         random_code = 0,
         shadow = options.shadow==='on' ? ' seg-box-shadow ' : ' ',
         caption_lightbox = '';

    html = htmlHeader + '<div id="'+options.id_main_div_photos+'" class="seg-instaportfolio-photos ' + options.hover_effect + '">';

    var allData = filterInstagram(jsonAPI.data, options);

    if (parseInt(options.number_photos, radix) > allData.length) {
      num_photos = allData.length;
    } else {
      num_photos = parseInt(options.number_photos, radix);
    }
  
    ///// LET`S APPEND DINAMICALLY THE HTML
    for (i = 0; i < num_photos; i++) {
      random_code = Math.floor((Math.random() * 999999));
      data = allData[i];
      caption_lightbox = (data.caption ? data.caption.text : '');

      if(data.type==="video"){
        lightboxCaption = data.videos.standard_resolution.url;
        data_src = 'data-sub-html="#seg-lightbox-html-' + random_code + '" data-html="#seg-lightbox-html-video-' + random_code + '" ';
      }else{
        lightboxCaption = data.images.standard_resolution.url;
        data_src = " data-src=" + lightboxCaption + '" href="'+ lightboxCaption +'" data-sub-html="#seg-lightbox-html-' + random_code + '" ';
      }

      html_li += '<div ' + (options.lightbox==="on" ? data_src : "") + ' class="seg-instaportfolio-photo ' + shadow + options.scroll_effect_class + ' ' +options.hover_photo_filter_effect + '">';

      html_li += '<img class="seg-main-image '+options.photo_effect+'" src="' + data.images.standard_resolution.url + '" alt=""/>';

      if(options.lightbox === "on"){
        html_li += '<div class="seg-lightbox">';
      }else{
        html_li += '<a href="' + data.link + '" target="_blank">';
      }

      html_li += '<div class="seg-instaportfolio-inside">';
      html_li += '<div class="seg-instaportfolio-captions">';


      // animated lines
      html_li += '<div class="seg-icon seg-icon-instagram animated-line">';
      if (options.display_icon_instagram === "on") {
        html_li += '<div class="seg-font-instagram seg-font-icon-' + options.icon_instagram_size + '" ' + (options.icon_instagram_colour ? ('style="color:' + options.icon_instagram_colour + ';"') : '') + '></div>';
      }

      html_li += '</div>';

      //// PHOTO DESCRIPTION
      if (options.display_photo_description === "on") {
        textCaption = "";
        if (data.caption === null) {
          textCaption = "";
        } else {
          textCaption = removeEmoji(data.caption.text);
          if(options.photo_description_limit){
            var words_limit =  parseInt(options.photo_description_limit, radix);
            textCaptionArray = textCaption.split(' ');
            if(textCaptionArray.length > words_limit){
              textCaption = "";
              for(j = 0 ; j < words_limit ; j++){
                textCaption += textCaptionArray[j] + " ";
              }
              textCaption += "...";
            }
          }
        }
        html_li += '<div class="seg-icon seg-insta-description">';
        html_li +=  '<div class="seg-font-' + options.photo_description_size + '" ' + (options.photo_description_colour ? ('style="color:' + options.photo_description_colour + ';"') : '') + '">' + textCaption + '</div>';
        html_li += '</div>';
      }

      //// PHOTO LIKES
      if (options.display_photo_likes === "on") {
        html_li += '<div class="seg-icon seg-icon-like seg-font-' + options.photo_likes_size + '">';
        html_li += '<div class="seg-font-heart seg-font-' + options.photo_likes_size + '" ' + (options.photo_likes_colour ? ('style="color:' + options.photo_likes_colour + ';"') : '') + ' class="fa fa-heart">&nbsp;' + commaSeparateNumber(data.likes.count) + '</div>';
        html_li += '</div>';
      }
      html_li += '</div><!-- .seg-instaportfolio-captions -->';

      ///// SOCIAL ICONS
      if(options.display_social_icons === "on"){
        html_li += '<div class="seg-social-icons" data-title="' + data.user.full_name + ' @' + data.user.username + '" data-caption="' + textCaption + '">';
        html_li +=  '<a href="#" data-social="facebook" class="seg-social-icon seg-social-facebook"><i class="seg-font-facebook"></i></a>';
        html_li +=  '<a href="#" data-social="twitter" class="seg-social-icon seg-social-twitter"><i class="seg-font-twitter"></i></a>';
        html_li +=  '<a href="#" data-social="pinterest" class="seg-social-icon seg-social-pinterest"><i class="seg-font-pinterest"></i></a>';
        html_li +=  '<a href="#" data-social="google-plus" class="seg-social-icon seg-social-gplus"><i class="seg-font-gplus"></i></a>';
        html_li += '</div>';
      }


      html_li += '</div><!-- .seg-instaportfolio-inside -->';
      if(options.lightbox === "on"){
        html_li += '</div>';
        if(data.type==="video"){
          html_li += '<div id="seg-lightbox-html-video-' + random_code + '" style="display:none;">';
          html_li +=   '<video class="lg-video-object lg-html5"  controls preload="none">';
          html_li +=     '<source src="' + lightboxCaption + '" type="video/mp4">Your browser does not support HTML5 video.';
          html_li +=   '</video>';
          html_li += '</div>';

          html_li += '<div id="seg-lightbox-html-' + random_code + '" style="display:none;">';
          html_li +=   '<div class="custom-html seg-lightbox-topbar">';
          html_li +=     '<h4>';
          html_li +=       '<a target="_blank" href="' + data.link + '">';
          html_li +=         '<img src="' + data.user.profile_picture + '" />';
          html_li +=       '</a>';
          html_li +=     '</h4>';
          html_li +=     '<div class="seg-lightbox-caption">'+caption_lightbox+'</div>';
          html_li +=   '</div>';
          html_li +=  '</div>';

        }else{
          html_li += '<div id="seg-lightbox-html-' + random_code + '" style="display:none;">';
          html_li +=   '<div class="custom-html seg-lightbox-topbar">';
          html_li +=     '<h4>';
          html_li +=       '<a target="_blank" href="' + data.link + '">';
          html_li +=         '<img src="' + data.user.profile_picture + '" />';
          html_li +=       '</a>';
          html_li +=     '</h4>';
          html_li +=     '<div class="seg-lightbox-caption">'+caption_lightbox+'</div>';
          html_li +=   '</div>';
          html_li += '</div>';
        }
      }else{
        html_li += '</a>';
      }
      html_li += '</div>';
    } // end for

    html += html_li;
    html += '</div><!-- .seg-instaportfolio-photos -->';
    if (parseInt(options.num_photos, radix) > 33) {
      html += '<div class="seg-load-more"><span next_max_id="' + jsonAPI.pagination.next_max_id + '"  >Load more...</span></div>';
    }else{
      if (options.load_more === "on") {
        if (options.instagram_mode === "User")
          html += '<div class="seg-load-more"><span next_max_id="' + jsonAPI.pagination.next_max_id + '"  >Load more...</span></div>';
        else if(options.instagram_mode === "Hashtag"){
          html += '<div class="seg-load-more"><span next_max_id="' + jsonAPI.pagination.next_max_tag_id + '"  >Load more...</span></div>';
        }else if(options.instagram_mode === "Location"){
          html += '<div class="seg-load-more"><span next_max_id="' + jsonAPI.pagination.next_max_id + '"  >Load more...</span></div>';
        }
      }
    }

    var cont = 0;
    mainDiv.append(html).find(".seg-main-image").load(function(){
      if(++cont == num_photos){
        justifyGallery(options);
        clickSocial();
        scrollEffect(options);
        $(options.main_div).find(".seg-spinner").remove();
      }
    });


    loadMore(options);

    var addCSS = "";

    if(options.responsive === "on"){
      //// IPAD
      addCSS += " @media only screen and (min-width : 768px) and (max-width : 1024px)  { ";
      addCSS +=  "  .seg-instaportfolio .seg-instaportfolio-photos li{ width: 33.3333333% !important;  } ";
      addCSS += " } ";
      //// PHONE
      addCSS += " @media only screen and (min-width : 375px) and (max-width : 667px) { ";
      addCSS += " .seg-instaportfolio .seg-instaportfolio-photos li{ width: 100% !important;  } ";
      addCSS += " } ";
      $('head').append('<style>' + addCSS + '</style>');
    }
  }

  function appendHTMLLoadMore(options, jsonAPI) {
    var num_photos = 0,
        mainDiv = $(options.main_div),
        liBoxElementFirst = $(options.main_div + ' .seg-instaportfolio-photos .seg-instaportfolio-photo:first-child'),
        html_li = "",
        radix = 10,
        animation = '',
        i = 0, 
        random_code = 0,
        textCaption = "",
        effects = ["fade", "scale"],
        shadow = options.shadow==='on' ? ' seg-box-shadow ' : ' ',
        caption_lightbox = '';

    var allData = filterInstagram(jsonAPI.data, options);

    if (parseInt(options.number_photos, radix) > allData.length)
      num_photos = allData.length;
    else
      num_photos = parseInt(options.number_photos, radix);

    ///// LET`S APPEND DINAMICALLY THE HTML
    for (i = 0; i < num_photos; i++) {
      html_li = "";
      
      random_code = Math.floor((Math.random() * 999999));
      data = allData[i];
      caption_lightbox = (typeof data.caption != 'undefined' ? data.caption.text : '');

      if(data.type==="video"){
        lightboxCaption = data.videos.standard_resolution.url;
        data_src = 'data-sub-html="#seg-lightbox-html-' + random_code + '" data-html="#seg-lightbox-html-video-' + random_code + '" ';
      }else{
        lightboxCaption = data.images.standard_resolution.url;
        data_src = " data-src=" + lightboxCaption + '" href="'+ lightboxCaption +'" data-sub-html="#seg-lightbox-html-' + random_code + '" ';
      }

      html_li += '<div ' + (options.lightbox==="on" ? data_src : "") + ' class="seg-instaportfolio-photo ' + shadow + ' ' +options.hover_photo_filter_effect + '">';

      html_li += '<img class="seg-main-image '+options.photo_effect+'" src="' + data.images.standard_resolution.url + '" alt=""/>';

      if(options.lightbox === "on"){
        html_li += '<div src="'+ data_src +'" class="seg-lightbox">';
      }else{
        html_li += '<a href="' + data.link + '" target="_blank">';
      }

      html_li += '<div class="seg-instaportfolio-inside">';
      html_li += '<div class="seg-instaportfolio-captions">';


      // animated lines
      html_li += '<div class="seg-icon seg-icon-instagram animated-line">';
      if (options.display_icon_instagram === "on") {
        html_li += '<div class="seg-font-instagram seg-font-icon-' + options.icon_instagram_size + '" ' + (options.icon_instagram_colour ? ('style="color:' + options.icon_instagram_colour + ';"') : '') + '></div>';
      }

      html_li += '</div>';

      //// PHOTO DESCRIPTION
      if (options.display_photo_description==="on") {
        textCaption = "";
        if (data.caption===null) {
          textCaption = "";
        } else {
          textCaption = removeEmoji(data.caption.text);
          if(options.photo_description_limit){
            var words_limit =  parseInt(options.photo_description_limit, radix);
            textCaptionArray = textCaption.split(' ');
            if(textCaptionArray.length > words_limit){
              textCaption = "";
              for(j = 0 ; j < words_limit ; j++){
                textCaption += textCaptionArray[j] + " ";
              }
              textCaption += "...";
            }
          }
        }
        html_li += '<div class="seg-icon seg-insta-description">';
        html_li +=  '<div class="seg-font-' + options.photo_description_size + '" ' + (options.photo_description_colour ? ('style="color:' + options.photo_description_colour + ';"') : '') + '">' + textCaption + '</div>';
        html_li += '</div>';
      }

      //// PHOTO LIKES
      if (options.display_photo_likes === "on") {
        html_li += '<div class="seg-icon seg-icon-like seg-font-' + options.photo_likes_size + '">';
        html_li += '<div class="seg-font-heart seg-font-' + options.photo_likes_size + '" ' + (options.photo_likes_colour ? ('style="color:' + options.photo_likes_colour + ';"') : '') + ' class="fa fa-heart">&nbsp;' + commaSeparateNumber(data.likes.count) + '</div>';
        html_li += '</div>';
      }
      html_li += '</div><!-- .seg-instaportfolio-captions -->';

      ///// SOCIAL ICONS
      if(options.display_social_icons === "on"){
        html_li += '<div class="seg-social-icons" data-title="' + data.user.full_name + ' @' + data.user.username + '" data-caption="' + textCaption + '">';
        html_li +=  '<a href="#" data-social="facebook" class="seg-social-icon seg-social-facebook"><i class="seg-font-facebook"></i></a>';
        html_li +=  '<a href="#" data-social="twitter" class="seg-social-icon seg-social-twitter"><i class="seg-font-twitter"></i></a>';
        html_li +=  '<a href="#" data-social="pinterest" class="seg-social-icon seg-social-pinterest"><i class="seg-font-pinterest"></i></a>';
        html_li +=  '<a href="#" data-social="google-plus" class="seg-social-icon seg-social-gplus"><i class="seg-font-gplus"></i></a>';
        html_li += '</div>';
      }

      html_li += '</div><!-- .seg-instaportfolio-inside -->';

      if(options.lightbox === "on"){
        html_li += '</div>';
        if(data.type==="video"){
          html_li += '<div id="seg-lightbox-html-video-' + random_code + '" style="display:none;">';
          html_li +=   '<video class="lg-video-object lg-html5"  controls preload="none">';
          html_li +=     '<source src="' + lightboxCaption + '" type="video/mp4">Your browser does not support HTML5 video.';
          html_li +=   '</video>';
          html_li += '</div>';

          html_li += '<div id="seg-lightbox-html-' + random_code + '" style="display:none;">';
          html_li +=   '<div class="custom-html seg-lightbox-topbar">';
          html_li +=     '<h4>';
          html_li +=       '<a target="_blank" href="' + data.link + '">';
          html_li +=         '<img src="' + data.user.profile_picture + '" />';
          html_li +=       '</a>';
          html_li +=     '</h4>';
          html_li +=     '<div class="seg-lightbox-caption">'+caption_lightbox+'</div>';
          html_li +=   '</div>';
          html_li +=  '</div>';

        }else{
          html_li += '<div id="seg-lightbox-html-' + random_code + '" style="display:none;">';
          html_li +=   '<div class="custom-html seg-lightbox-topbar">';
          html_li +=     '<h4>';
          html_li +=       '<a target="_blank" href="' + data.link + '">';
          html_li +=         '<img src="' + data.user.profile_picture + '" />';
          html_li +=       '</a>';
          html_li +=     '</h4>';
          html_li +=     '<div class="seg-lightbox-caption">'+caption_lightbox+'</div>';
          html_li +=   '</div>';
          html_li += '</div>';
        }
      }else{
        html_li += '</a>';
      }
      html_li += '</div>';

      $(mainDiv).find(".seg-instaportfolio-photos").append(html_li);
    }

    var cont = 0;
    // $(mainDiv).find(".seg-main-image").load(function(){
    //   if(++cont == num_photos){
    //     justifyGallery(options);
    //     clickSocial();
    //     $(options.main_div).find(".seg-spinner").remove();
    //   }
    // });

    $(mainDiv).find(".seg-instaportfolio-photos .seg-instaportfolio-photo:last-child img").load(function(){ 
      justifyGallery(options);
      clickSocial();
      $(options.main_div).find(".seg-spinner").remove();
      lightboxGallery(options, true);
    });

    if (options.instagram_mode === "User"){
      $(mainDiv).find(".seg-load-more span").attr("next_max_id", jsonAPI.pagination.next_max_id);
    } else if(options.instagram_mode === "Hashtag"){
      $(mainDiv).find(".seg-load-more span").attr("next_max_id", jsonAPI.pagination.next_max_tag_id);
    } else if(options.instagram_mode === "Location"){
        $(mainDiv).find(".seg-load-more span").attr("next_max_id", jsonAPI.pagination.next_max_id);
    }
  }

  function nFormatter(num) {
       if (num >= 1000000000) {
          return (num / 1000000000).toFixed(1).replace(/\.0$/, '') + 'g';
       }
       if (num >= 1000000) {
          return (num / 1000000).toFixed(1).replace(/\.0$/, '') + 'm';
       }
       if (num >= 1000) {
          return (num / 1000).toFixed(1).replace(/\.0$/, '') + 'k';
       }
       return num;
  }

  function buildHeaderHTML(jsonAPIUserInfo, options){
    var returnHTML = "";
    var profileURLInstagram = "";
    var profilePicture = "";
    var locationName = "";

    //// HEADER BACKGROUND
    if(options.header_background)
      $(options.main_div).css({ "background" : options.header_background });


    ///// LETS BUILD HEADER HTML
    returnHTML += '<div class="seg-header" '  + (options.header_text_colour ? ('style="color: ' + options.header_text_colour + '"') : "")  + '>';

    if(options.instagram_mode === "User"){

      profileURLInstagram = "http://instagram.com/" + jsonAPIUserInfo.data.username;
      profilePicture = jsonAPIUserInfo.data.profile_picture;

      returnHTML +=  '<div class="seg-header-row-1" style="background:' + options.header_panel_button_colour + '">';

      returnHTML +=    '<div class="seg-header-profile-picture">';
      returnHTML +=     '<a target="_blank" href="' + profileURLInstagram + '"><img alt="' + jsonAPIUserInfo.data.full_name + '" src="' + profilePicture + '" /></a>';
      returnHTML +=    '</div>';

      returnHTML +=    '<div class="seg-font-instagram"></div>';

      returnHTML +=  '</div><!-- .seg-header-row-1 -->';


      returnHTML +=    '<ul class="seg-header-counts">';
      returnHTML +=     '<li class="seg-header-posts"><i>' + nFormatter(jsonAPIUserInfo.data.counts.media) + '</i><span>posts</span></li>';
      returnHTML +=     '<li class="seg-header-followed"><i>' + nFormatter(jsonAPIUserInfo.data.counts.followed_by) + '</i><span>followers</span></li>';
      returnHTML +=     '<li class="seg-header-followers"><i>' + nFormatter(jsonAPIUserInfo.data.counts.follows) + '</i><span>following</span></li>';
      returnHTML +=    '</ul><!-- .seg-header-counts -->';

      returnHTML +=    '<a target="_blank" href="' + profileURLInstagram + '" style="color: ' + options.header_panel_button_colour + '; border: 1px solid ' + options.header_panel_button_colour + ';" class="seg-follow-instagram">+&nbsp;follow</a>';

      returnHTML +=  '<div class="seg-header-name" style="border-left: 1px solid ' + ( options.header_text_colour ? options.header_text_colour : "black" ) + ';">';
      returnHTML +=    jsonAPIUserInfo.data.full_name + ' <a '  + (options.header_text_colour ? ('style="color: ' + options.header_text_colour + '"') : "")  + ' target="_blank" href="' + profileURLInstagram + '">@' + jsonAPIUserInfo.data.username + '</a>';
      returnHTML +=  '</div>';
      returnHTML +=  '<div class="seg-header-bio">';
      returnHTML +=    jsonAPIUserInfo.data.bio;
      returnHTML +=  '</div>';
    }else if(options.instagram_mode === "Hashtag"){
      returnHTML +=  '<div class="seg-header-row-1 seg-header-hashtag" style="background:' + options.header_panel_button_colour + '">';
      returnHTML +=    '<span>#' + options.username + '</span>';
      returnHTML +=    '<div class="seg-font-instagram"></div>';
      returnHTML +=  '</div><!-- .seg-header-row-1 -->';
    }else if(options.instagram_mode === "Location"){
      locationName = jsonAPIUserInfo.data[0].location.name;
      returnHTML +=  '<div class="seg-header-row-1 seg-header-hashtag" style="background:' + options.header_panel_button_colour + '">';
      returnHTML +=    '<span>' + locationName + '</span>';
      returnHTML +=    '<div class="seg-font-instagram"></div>';
      returnHTML +=  '</div><!-- .seg-header-row-1 -->';
    }

    returnHTML += '</div><!-- .seg-header -->';

    return returnHTML;
  }

  function getPhotosUser(options, userIDParam) {
    var mainDiv = $(options.main_div);
    var userID = userIDParam;
    var urlAPI = 'https://api.instagram.com/v1/users/' + userID + '/media/recent/?callback=?';
    var param = {
      count: options.number_photos,
      access_token: options.access_token
    };
    $.ajax({
      dataType: "jsonp",
      url: urlAPI,
      data: param,
      success: function (jsonAPI) {
        if (jsonAPI.meta.code === 200) {
          if( options.display_header !== "on"){
            appendHTML(options, jsonAPI, "");
          }else{
            urlAPI = 'https://api.instagram.com/v1/users/' + userID + '?callback=?';
            param = {
              count: '1',
              access_token: options.access_token
            };
            $.ajax({
              dataType: "jsonp",
              url: urlAPI,
              data: param,
              success: function (jsonAPIUserInfo) {
                appendHTML(options, jsonAPI, buildHeaderHTML(jsonAPIUserInfo, options));
              }
            });
          }
        } else {
          displayWarning(options, jsonAPI, "");
        }
      }
    });
  }

  function getUserID(options) {
    var mainDiv = $(options.main_div);
    var urlAPI = 'https://api.instagram.com/v1/users/search?callback=?';
    var param = {
                  access_token: options.access_token,
                  q: options.username
                };
    var userID = "";

    if(isNumeric(options.username)){
      getPhotosUser(options, options.username);
    }else{
      $.ajax({
        dataType: "jsonp",
        url: urlAPI,
        data: param,
        success: function (jsonAPI) {
          if (jsonAPI.meta.code === 200) {
            for(var i = 0 ; i <= (jsonAPI.data.length-1) ; i++){
              if( jsonAPI.data[i].username === options.username ){
                userID = jsonAPI.data[i].id;
              }
            }
            if (jsonAPI.meta.code === 200) {
              if (jsonAPI.data.length > 0 && userID) {
                getPhotosUser(options, userID);
              } else {
                displayWarning(options, jsonAPI, "No user found!");
              }
            } else {
              displayWarning(options, jsonAPI, "");
            }
          }else{
            displayWarning(options, jsonAPI, 'Missing Access Token! Generate your Access Token <a href="http://instagramwordpress.rafsegat.com/docs/get-access-token/" target="_blank">here</a>.');
          }
        }
      });
    }
  }

  function getPhotosUserLoadMore(options, userID, next_max_id_param) {
    var mainDiv = $(options.main_div);
    var urlAPI = 'https://api.instagram.com/v1/users/' + userID + '/media/recent/?callback=?';
    var param = {
      count: options.number_photos,
      access_token: options.access_token,
      max_id: next_max_id_param
    };
    $.ajax({
      dataType: "jsonp",
      url: urlAPI,
      data: param,
      success: function (jsonAPI) {
        if (jsonAPI.meta.code === 200) {
          appendHTMLLoadMore(options, jsonAPI);
        } else {
          displayWarning(options, jsonAPI, "");
        }
      }
    });
  }

  function getUserIDLoadMore(options, next_max_id_param) {
    var mainDiv = $(options.main_div);
    var urlAPI = 'https://api.instagram.com/v1/users/search?callback=?';
    var userID = "";
    var param = {
      count: '1',
      access_token: options.access_token,
      q: options.username
    };
    if(isNumeric(options.username)){
      getPhotosUserLoadMore(options, options.username, next_max_id_param);
    }else{
      $.ajax({
        dataType: "jsonp",
        url: urlAPI,
        data: param,
        success: function (jsonAPI) {
          if (jsonAPI.meta.code === 200) {
            if (jsonAPI.data.length > 0) {
              userID = jsonAPI.data[0].id;
              getPhotosUserLoadMore(options, userID, next_max_id_param);
            } else {
              displayWarning(options, jsonAPI, "No user found!");
            }
          } else {
            displayWarning(options, jsonAPI, "");
          }
        }
      });
    }
  }

  function getPhotosHashtag(options) {
    var mainDiv = $(options.main_div);
    var urlAPI = "https://api.instagram.com/v1/tags/" + options.username + "/media/recent?callback=?";
    var param = {
      count: options.number_photos,
      access_token: options.access_token
    };
    $.ajax({
      dataType: "jsonp",
      url: urlAPI,
      data: param,
      success: function (jsonAPI) {
        if (jsonAPI.meta.code === 200) {
          if(options.display_header === "on")
            appendHTML(options, jsonAPI, buildHeaderHTML("", options));
          else
            appendHTML(options, jsonAPI, "");
        } else {
          displayWarning(options, jsonAPI, "");
        }
      }
    });
  }

  function getPhotosHashtagLoadMore(options, next_max_id_param) {

    var mainDiv = $(options.main_div);
    var urlAPI = "https://api.instagram.com/v1/tags/" + options.username + "/media/recent?callback=?";
    var param = {
      count: options.number_photos,
      access_token: options.access_token,
      max_id: next_max_id_param
    };
    $.ajax({
      dataType: "jsonp",
      url: urlAPI,
      data: param,
      success: function (jsonAPI) {
        if (jsonAPI.meta.code === 200) {
          appendHTMLLoadMore(options, jsonAPI);
        } else {
          displayWarning(options, jsonAPI, "");
        }
      }
    });
  }

  function getIDLocation(options) {
    var mainDiv = $(options.main_div);
    var urlAPI = "https://api.instagram.com/v1/locations/" + options.username + "/media/recent";
    var param = {
      access_token: options.access_token,
      count: options.number_photos
    };
    $.ajax({
      dataType: "jsonp",
      url: urlAPI,
      data: param,
      success: function (jsonAPI) {
        if (jsonAPI.meta.code === 200) {
          if (jsonAPI.data.length > 0) {
            if(options.display_header === "on")
              appendHTML(options, jsonAPI, buildHeaderHTML(jsonAPI, options));
            else
              appendHTML(options, jsonAPI, "");
          }else{
            displayWarning(options, jsonAPI, "Location without photos!");
          }
        } else {
          displayWarning(options, jsonAPI, "Location not found!");
        }
      }
    });
  }

  function getPhotosLocationLoadMore(options, next_max_id_param) {
    var mainDiv = $(options.main_div);
    var urlAPI = "https://api.instagram.com/v1/locations/" + options.username + "/media/recent";
    var param = {
      count: options.number_photos,
      access_token: options.access_token,
      max_id: next_max_id_param
    };
    $.ajax({
      dataType: "jsonp",
      url: urlAPI,
      data: param,
      success: function (jsonAPI) {
        if (jsonAPI.meta.code === 200) {
          if (jsonAPI.data.length > 0) {
            if (jsonAPI.meta.code === 200) {
                appendHTMLLoadMore(options, jsonAPI);
              } else {
                displayWarning(options, jsonAPI, "");
              }
            }
        } else {
          displayWarning(options, jsonAPI, "Location not found!");
        }
      }
    });
  }

  function clickSocial(){
    $(".seg-instaportfolio .seg-social-icons a").click(function(e){
      e.preventDefault();
      var social = $(this).data("social");
      var $tile = $(this).parents(".seg-instaportfolio-inside").first();
      var image = $tile.find("img").attr("src");

      var text = $.trim($tile.find(".seg-social-icons").data("caption"));
      if(! text.length)
        text = document.title;

      var title = $.trim($tile.find(".seg-social-icons").data("title"));
      if(! title.length)
        title = document.title;
      else
        text = text + " by " + title;

      if(social == "facebook") {
        var url = "https://www.facebook.com/dialog/feed?app_id=739648656157106&"+
                            "link="+encodeURIComponent(location.href)+"&" +
                            "display=popup&"+
                            "name="+encodeURIComponent(title)+"&"+
                            "caption=&"+
                            "description="+encodeURIComponent(text)+"&"+
                            "picture="+encodeURIComponent(qualifyURL(image))+"&"+
                            "ref=share&"+
                            "actions={%22name%22:%22View%20the%20gallery%22,%20%22link%22:%22"+encodeURIComponent(location.href)+"%22}&"+
                            "redirect_uri=" + encodeURIComponent(location.href);


                var w = window.open(url, "ftgw", "location=1,status=1,scrollbars=1,width=600,height=400");
                w.moveTo((screen.width / 2) - (300), (screen.height / 2) - (200));
      }

      if(social == "twitter") {
        var w = window.open("https://twitter.com/intent/tweet?url=" + encodeURI(location.href.split('#')[0]) + "&text=" + encodeURI(text), "ftgw", "location=1,status=1,scrollbars=1,width=600,height=400");
                w.moveTo((screen.width / 2) - (300), (screen.height / 2) - (200));
      }

      if(social == "pinterest") {
        var url = "http://pinterest.com/pin/create/button/?url=" + encodeURIComponent(location.href) + "&description=" + encodeURI(text);

                url += ("&media=" + encodeURIComponent(qualifyURL(image)));

                var w = window.open(url, "ftgw", "location=1,status=1,scrollbars=1,width=600,height=400");
                w.moveTo((screen.width / 2) - (300), (screen.height / 2) - (200));
      }

      if(social == "google-plus") {
        var url = "https://plus.google.com/share?url=" + encodeURI(location.href);

                var w = window.open(url, "ftgw", "location=1,status=1,scrollbars=1,width=600,height=400");
                w.moveTo((screen.width / 2) - (300), (screen.height / 2) - (200));
      }

    });
  }

  function loadMore_call(options, load_more_obj) {
    var next_max_id = $(load_more_obj).attr("next_max_id");
    if (options.instagram_mode === "User")
      getUserIDLoadMore(options, next_max_id);
    if (options.instagram_mode === "Hashtag")
      getPhotosHashtagLoadMore(options, next_max_id);
    if (options.instagram_mode === "Location")
      getPhotosLocationLoadMore(options, next_max_id);
  }

  function loadMore(options) {
    var button_load_more = $(options.main_div).find(".seg-load-more span");
    if (options.load_more === "on") {
      button_load_more.click(function () {
        button_load_more.append('<div class="seg-spinner" style="background-color:'+options.loading_color+';"></div>');
        loadMore_call(options, $(this));
      });
    }
  }

  function loadAll(options){
      ///// TRIGGER EFFECTS WHEN SCROLL THE PAGE
      //scrollEffect(options);
      //// LOAD MORE
      //loadMore(options);
      //// SOCIAL ICONS
      //clickSocial();
  }

  $.fn.seg_instaportfolio = function (options) {

    cssOptions(options);

    if (options.instagram_mode === "User")
      getUserID(options);

    if (options.instagram_mode === "Hashtag")
      getPhotosHashtag(options);

    if (options.instagram_mode === "Location")
      getIDLocation(options);

    $(window).load(function(){
      if(options.lightbox === "on"){
        // $(options.main_div + " .seg-instaportfolio-photos").lightGallery({
        //   videoMaxWidth:'640px'
        // });
        lightboxGallery(options, false);
      }
    });
  };

}(jQuery));
