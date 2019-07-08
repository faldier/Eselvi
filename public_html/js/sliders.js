$(document).ready(function() {

  $("#reviewsSlider").lightSlider({
      item: 2,
      autoWidth: false,
      slideMove: 2,
      auto: true,
      speed:4000,
      loop:true,
      pause:8000,
      controls:false,
      pager:false,
  });

  $("#singleReviewsSlider").lightSlider({
      item: 1,
      autoWidth: false,
      slideMove: 1,
      auto: true,
      speed:4000,
      loop:true,
      pause:8000,
      controls:false,
      pager:false,
  });

  $("#bannerSlider").lightSlider({
      item: 1,
      autoWidth: false,
      slideMove: 1,
      auto: true,
      speed:2000,
      loop:true,
      pause:5000,
      controls:false,
  });

  $("#productImgSlider").lightSlider({
    gallery: true,
    item: 1,
    loop:true,
    thumbItem: 2,
    currentPagerPosition: 'middle',
    
  });

  var slider = $("#promoProductsSlider").lightSlider({
    item: 4,
    autoWidth: false,
    slideMove: 1,
    auto: true,
    speed:2000,
    loop:true,
    pause:6000,
    controls:false,
    pager:false,
  });

  $('#goToPrevSlide').click(function(){
    slider.goToPrevSlide();
  });

  $('#goToNextSlide').click(function(){
    slider.goToNextSlide();
  });

});
