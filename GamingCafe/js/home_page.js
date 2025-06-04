var targetCanMove = false;
document.addEventListener("DOMContentLoaded", function () {
  let currentSlide = 0;
  const slides = document.querySelectorAll(".slid");
  const dots = document.querySelectorAll(".dot");

  function changeSlide(index) {
      slides.forEach(slide => slide.classList.remove("active"));
      dots.forEach(dot => dot.classList.remove("active"));

      slides[index].classList.add("active");
      dots[index].classList.add("active");

      currentSlide = index;
  }

  // Auto-slide every 5 seconds
  setInterval(() => {
      let nextSlide = (currentSlide + 1) % slides.length;
      changeSlide(nextSlide);
  }, 5000);

  // Click on dots to change slides manually
  dots.forEach((dot, index) => {
      dot.addEventListener("click", () => changeSlide(index));
  });

  // Initialize first slide as active
  changeSlide(0);
});


function loginBtn() {
  window.open("login.php","_self")
}

function bulletBtn() {
  var regbtn = $("#regbtn");
  var landarea = $(".homearea .land");

  regbtn.removeClass("bullet-fire");
  landarea.removeClass("shake");
  landarea.removeClass("shake2");

  regbtn.addClass("bullet-fire");
  landarea.addClass('shake');

  setTimeout(function () {
    regbtn.css("display", "none");
    window.open("register.php","_self");
  }, 2000);
}

function shakeWindow() {
  var landarea = $(".homearea .land");
  landarea.removeClass("shake");
  landarea.removeClass("shake2");
  window.requestAnimationFrame(function () {
    landarea.addClass('shake2');
  });
}
function dashConuter(element, end) {
  element.each(function () {
    $(this).prop('Counter', 0).animate({
      Counter: end
    }, {
      duration: 4000,
      easing: 'swing',
      step: function (now) {
        $(this).text(Math.ceil(now));
      }
    });
  });
}

function setDashData() {
  $.ajax({
    type: "get",
    url: "/home/view",
    success: function (response) {
      dashConuter($("#dashcomputer"), response[0].computers)
      dashConuter($("#dashgame"), response[0].games)
      dashConuter($("#dashuser"), response[0].users)
      dashConuter($("#dashres"), response[0].reservations)
    }
  });
}

function targetMove(e) {
  // mouseX = e.pageX;
  // mouseY = e.pageY;
  // var back = $(".homearea .land .wall .subcontainer2 .back");
  // $(".target").css({"top": (mouseY - back.top - $(".target").getBoundingClientRect().width / 2) + "px", "left": (mouseX - back.left - $(".target").getBoundingClientRect().width / 2) + "px"});

}

$(document).ready(function () {
  setDashData();

  var targetBack = $(".homearea .land .wall .subcontainer2");
  var targetImg = $(".homearea .land .wall .subcontainer2 .target .circle");
  var target = $(".target");

  targetBack.on("click", function () {
    if(targetCanMove){
    shakeWindow();
    }else{
      targetCanMove = true;
      targetImg.css("background-image", 'url("css/img/train.jpg")');
      targetBack.css("cursor","none");
    }
  });
  targetBack.on("mousemove", function (e) {
    if (targetCanMove) {
      var mouseX = e.offsetX;
      var mouseY = e.offsetY;
      target.css({
        "top": (mouseY - target.width() / 2) + "px",
        "left": (mouseX - target.width() / 2) + "px",
      });
      targetImg.css("background-position", `${-(mouseX - targetImg.width() / 2)}px ${-(mouseY - targetImg.height() / 2)}px`);
    }
  });
  targetBack.on("mouseout", function () {
    var pos = targetBack.width() / 2 - target.width() / 2;
    target.animate({
      top: `${pos}px`,
      left: `${pos}px`,
    });
    targetImg.css({"background-image": 'none', "background-position" : "center"});
    targetBack.css("cursor","default");
    targetCanMove = false;
  });

  $("section").fuwatto({
    duration: 3000,
  });
});
// document.addEventListener("mousemove", function() {
//   targetMove(event);
// });


// function targetMove(e) {
//   var cursor = document.getElementsByClassName("target");
//   mouseX = e.clientX;
//   mouseY = e.clientY;
//   console.log(cursor);
//   cursor.style.left = (mouseX - 55) + "px";
//   cursor.style.top = (mouseY - 55) + "px";


// }

// function targetMove(e) {
//   var cursor = document.getElementsByClassName("target")[0];
//   var back = document.getElementsByClassName("back")[0].getBoundingClientRect();
//   var cursorImg = document.querySelector(".target .circle");
//   var mouseX = e.clientX;
//   var mouseY = e.clientY;
//   if (cursor) {
//     cursor.style.left = (mouseX - back.left - cursor.getBoundingClientRect().width / 2) + "px";
//     cursor.style.top = (mouseY - back.top - cursor.getBoundingClientRect().width / 2) + "px";
//     cursorImg.style.backgroundPosition = "-" + mouseX / 2 + "px -" + mouseY / 2 + "px";
//   }
// }

// window.onload = function () {
//   var back = document.querySelector(".homearea .land .wall .subcontainer2 .back");
//   if (back) {
//     back.addEventListener("mousemove", targetMove);
//     back.addEventListener("click", shakeWindow);
//   }
// }