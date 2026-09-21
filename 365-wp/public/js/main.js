(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    var toggle = document.querySelector(".nav-toggle");
    var nav = document.getElementById("primary-nav");

    if (toggle && nav) {
      toggle.addEventListener("click", function () {
        var isOpen = nav.classList.toggle("is-open");
        toggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
      });
    }

    var parents = document.querySelectorAll(".primary-nav .has-children > a");
    parents.forEach(function (link) {
      link.addEventListener("click", function (e) {
        if (window.innerWidth <= 780) {
          var li = link.parentElement;
          var alreadyOpen = li.classList.contains("is-expanded");
          document.querySelectorAll(".primary-nav li.is-expanded").forEach(function (el) {
            el.classList.remove("is-expanded");
          });
          if (!alreadyOpen) {
            e.preventDefault();
            li.classList.add("is-expanded");
          }
        }
      });
    });
  });
})();
