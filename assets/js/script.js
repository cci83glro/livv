$(function () {
    $('.nav-btn').on('click', function () {
        $(this).toggleClass('open');
    });

    var currentIndex = 0;
    var $backgroundImage = $(".image-infinite-bg");
    var images = $backgroundImage.data('images');

    function changeBackgroundImage() {
        if (!images || images.length == 0) {
            return;
        }
        // Mengubah gambar latar belakang dengan indeks berikutnya dalam array
        $backgroundImage.removeClass('animation-bg');
        currentIndex = (currentIndex + 1) % images.length;
        var imagePath = images[currentIndex];
        $backgroundImage.css("background-image", "url('" + imagePath + "')");
        $backgroundImage[0].offsetHeight;
        $backgroundImage.addClass('animation-bg');

    }

    changeBackgroundImage();
    setInterval(changeBackgroundImage, 5000);
});

$.fn.isValid = function(){
    return this[0].checkValidity();
}

function toggleMenuItem(item) {
    $(item).toggleClass('show');
    var dropdownMenu = $($(item).siblings('.dropdown-menu')).toggleClass('show');
}

$(window).ready(function() {
    $(window).scroll(function() {
      var scroll = $(window).scrollTop();
       if (scroll >100) {
        $("#header").addClass('glass-effect');
       } else {
        $("#header").removeClass("glass-effect");
       }
     })
   })