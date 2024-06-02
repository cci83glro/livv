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

function validateRecaptcha() {
    var response = grecaptcha.getResponse();
    if (response.length == 0) {
        alert("Udfør venligst reCAPTCHA valideringen!");
        return false;
    } else {
        return true;
    }
}

$(window).on('scroll', function () {
    if ($(this).scrollTop() > 200) {
        $('#scroll-top').fadeIn(200);
    } else {
        $('#scroll-top').fadeOut(200);
    }
});

$('#scroll-top').on('click', function (event) {
    event.preventDefault();
    $('html, body').animate({
        scrollTop: 0
    }, 1000);
});

const monthNames = ["jan", "feb", "mar", "apr", "maj", "juni", "juli", "aug", "sep", "okt", "nov", "dec"];

function formatDateToDisplay(dateStr) {
    const [year, month, day] = dateStr.split('-');
    return `${day} ${monthNames[parseInt(month, 10) - 1]} ${year}`;
}

function formatDateToValue(dateStr) {
    const [day, month, year] = dateStr.split(' ');
    const monthIndex = monthNames.indexOf(month);
    const monthNumber = (monthIndex + 1).toString().padStart(2, '0');
    return `${year}-${monthNumber}-${day}`;
}

$('.date-picker').on('focus', function() {
    $(this).attr('type', 'date');
    const actualDate = $(this).attr('data-actual-date');
    if (actualDate) {
        $(this).val(actualDate);
    }
});

$('.date-picker').on('blur', function() {
    $(this).attr('type', 'text');
    if ($(this).val()) {
        const formattedValue = formatDateToDisplay($(this).val());
        if (!isNaN(Date.parse($(this).val()))) {
            $(this).attr('data-actual-date', $(this).val());
            $(this).val(formattedValue);
        }
    }
});

$(window).ready(function() {
    $(window).scroll(function() {
      var scroll = $(window).scrollTop();
       if (scroll >100) {
        $("#header").addClass('glass-effect');
       } else {
        $("#header").removeClass("glass-effect");
       }
     })

    $('a.nav-link.dropdown-content').unbind();
    $('a.dropdown-content').click(function(){
        $(this).next('.dropdown-menu').slideToggle();
    });    
})