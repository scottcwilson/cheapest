$(function(){
  // to top
  $().UItoTop({
    easingType: 'easeOutQuart'
  });
  // overlay hide
  $( ".close_msg" ).click(function() {
    $( ".layer_cart_overlay, .msg_hide, .messageStackSuccess.larger, .messageStackError.larger, .messageStackCaution.larger, .messageStackWarning" ).css("display","none");
  });
});
// smooth scrolling
$(function() {
    $.srSmoothscroll({
        step: 170,
        speed: 800
    });
});
$(function(){
   $('.messageStackSuccess').delay(4000).fadeOut('slow');
   $('.messageStackCaution').delay(4000).fadeOut('slow');
   $('.messageStackError').delay(4000).fadeOut('slow');
  });
$(function(){
  $('#relatedProducts').ready(function(){
    $( ".clearBoth" ).remove();
  });
  $().addClass(function(){
    $( ".clearBoth" ).remove();
  });
});
$(function(){
  $( ".mega-menu li" ).has('.dropdown').append( "<span class='plus'></span>" );
  $( ".cat-title" ).click(function() {
    $( "#mega-wrapper" ).stop(true).slideToggle(function() {
       $(".cat-title").stop(true).toggleClass('open');
    });
  });
  $( ".mega-menu li .plus" ).click(function() {
    $(this).parent().find( ".dropdown" ).slideToggle(function() {
      $(this).next().stop(true).toggleClass('open', $(this).is(":visible"));
    });
  });
  $(".categories_btn").click(function() {
    $("#tm_categories_block").slideToggle( "slow", function(){
    $("#tm_categories_block").prev().toggleClass("curr", $(this).is(":visible"));
    $("#tm_categories_block").stop();
    });
  });
});
    $(function(){
      if ($(window).width() <= 768){

        //footer accordion
          $(".title_btn1").click(function() {
            $(".ezpagesFooterCol.col1").slideToggle( "slow", function(){
            $(".ezpagesFooterCol.col1").prev().toggleClass("curr", $(this).is(":visible"));
            $(".ezpagesFooterCol.col1").stop();
            });
          });
          $(".title_btn2").click(function() {
            $(".account_list").slideToggle( "slow", function(){
            $(".account_list").prev().toggleClass("curr", $(this).is(":visible"));
            $(".account_list").stop();
            });
          });
          $(".title_btn3").click(function() {
            $(".social_list").slideToggle( "slow", function(){
            $(".social_list").prev().toggleClass("curr", $(this).is(":visible"));
            $(".social_list").stop();
            });
          });
          $(".title_btn4").click(function() {
            $(".contact_list").slideToggle( "slow", function(){
            $(".contact_list").prev().toggleClass("curr", $(this).is(":visible"));
            $(".contact_list").stop();
            });
          });
          
      //column accordion
        $( "#tm_categories_block .module-heading" ).click(function() {
          $( "#tm_categories_block #tm_categories" ).slideToggle(function() {
              $("#tm_categories_block .module-heading").toggleClass('open', $(this).is(":visible"));
            });
        });
        $( "#module_information .module-heading" ).click(function() {
          $( "#module_information .block_content" ).slideToggle(function() {
             $("#module_information .module-heading").toggleClass('open');
          });
        });
        $( "#module_languages .module-heading" ).click(function() {
          $( "#module_languages .block_content" ).slideToggle(function() {
            $("#module_languages .module-heading").toggleClass('open');
          });
        });
        $( "#module_shoppingcart .module-heading" ).click(function() {
          $( "#module_shoppingcart .block_content" ).slideToggle(function() {
            $("#module_shoppingcart .module-heading").toggleClass('open');
          });
        });
        $( "#module_reviews .module-heading" ).click(function() {
          $( "#module_reviews .block_content" ).slideToggle(function() {
            $("#module_reviews .module-heading").toggleClass('open');
          });
        });
        $( "#module_bestsellers .module-heading" ).click(function() {
          $( "#module_bestsellers .block_content" ).slideToggle(function() {
            $("#module_bestsellers .module-heading").toggleClass('open');
          });
        });
        $( "#module_whosonline .module-heading" ).click(function() {
          $( "#module_whosonline .block_content" ).slideToggle(function() {
            $("#module_whosonline .module-heading").toggleClass('open');
          });
        });
        $( "#module_currencies .module-heading" ).click(function() {
          $( "#module_currencies .block_content" ).slideToggle(function() {
            $("#module_currencies .module-heading").toggleClass('open');
          });
        });
        $( "#module_moreinformation .module-heading" ).click(function() {
          $( "#module_moreinformation .block_content" ).slideToggle(function() {
            $("#module_moreinformation .module-heading").toggleClass('open');
          });
        });
        $( "#module_search .module-heading" ).click(function() {
          $( "#module_search .block_content" ).slideToggle(function() {
            $("#module_search .module-heading").toggleClass('open');
          });
        });
        $( "#module_ezpages .module-heading" ).click(function() {
          $( "#module_ezpages .block_content" ).slideToggle(function() {
            $("#module_ezpages .module-heading").toggleClass('open');
          });
        });
        $( "#module_manufacturers .module-heading" ).click(function() {
          $( "#module_manufacturers .block_content" ).slideToggle(function() {
            $("#module_manufacturers .module-heading").toggleClass('open');
          });
        });
        $( "#module_whatsnew .module-heading" ).click(function() {
          $( "#module_whatsnew .block_content" ).slideToggle(function() {
            $("#module_whatsnew .module-heading").toggleClass('open');
          });
        });
        $( "#module_featured .module-heading" ).click(function() {
          $( "#module_featured .block_content" ).slideToggle(function() {
            $("#module_featured .module-heading").toggleClass('open');
          });
        });
        $( "#module_specials .module-heading" ).click(function() {
          $( "#module_specials .block_content" ).slideToggle(function() {
            $("#module_specials .module-heading").toggleClass('open');
          });
        });
        $( "#module_orderhistory .module-heading" ).click(function() {
          $( "#module_orderhistory .block_content" ).slideToggle(function() {
            $("#module_orderhistory .module-heading").toggleClass('open');
          });
        });
      }
    });

//carousel
$(function(){
  $('#fcarousel').carouFredSel({
    responsive: true,
    width: '100%',
    scroll: 1,
    auto: false,
    prev: '#prev2',
    next: '#next2',
    items: {
      width: 222,
      height: 290,  //  optionally resize item-height
    visible: {
      min: 1,
      max: 5
      }
    }
  });
});
//categories
$(function(){
  $("#tm_categories .level_1 li").has('.has_sub').append( "<span class='grower CLOSE'></span>");
  $('.grower').click(function(){
    $(this).parent().find('.level_2.has_sub').slideToggle();
    $(this).toggleClass('CLOSE');
    $(this).toggleClass('OPEN');
  });
});
$(function(){
    $('#shopping_cart').click(function(){
    $(this).find( "#shopping_cart_content" ).stop().slideToggle( "slow");
  });
});
