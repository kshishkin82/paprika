(function () {
  "use strict";

  function createLightbox() {
    var overlay = document.createElement("div");
    overlay.className = "gallery-lightbox";
    overlay.innerHTML =
      '<div class="gallery-lightbox__dialog" role="dialog" aria-modal="true">' +
      '<button class="gallery-lightbox__nav gallery-lightbox__nav--prev" type="button" aria-label="Previous image"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M169.4 297.4C156.9 309.9 156.9 330.2 169.4 342.7L361.4 534.7C373.9 547.2 394.2 547.2 406.7 534.7C419.2 522.2 419.2 501.9 406.7 489.4L237.3 320L406.6 150.6C419.1 138.1 419.1 117.8 406.6 105.3C394.1 92.8 373.8 92.8 361.3 105.3L169.3 297.3z"/></svg></button>' +
      '<button class="gallery-lightbox__nav gallery-lightbox__nav--next" type="button" aria-label="Next image"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/></svg></button>' +
      '<button class="gallery-lightbox__close" type="button" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M183.1 137.4C170.6 124.9 150.3 124.9 137.8 137.4C125.3 149.9 125.3 170.2 137.8 182.7L275.2 320L137.9 457.4C125.4 469.9 125.4 490.2 137.9 502.7C150.4 515.2 170.7 515.2 183.2 502.7L320.5 365.3L457.9 502.6C470.4 515.1 490.7 515.1 503.2 502.6C515.7 490.1 515.7 469.8 503.2 457.3L365.8 320L503.1 182.6C515.6 170.1 515.6 149.8 503.1 137.3C490.6 124.8 470.3 124.8 457.8 137.3L320.5 274.7L183.1 137.4z"/></svg></button>' +
      '<img class="gallery-lightbox__img" alt="">' +
      "</div>";
    document.body.appendChild(overlay);

    return {
      overlay: overlay,
      image: overlay.querySelector(".gallery-lightbox__img"),
      closeButton: overlay.querySelector(".gallery-lightbox__close"),
      prevButton: overlay.querySelector(".gallery-lightbox__nav--prev"),
      nextButton: overlay.querySelector(".gallery-lightbox__nav--next"),
    };
  }

  var lb = createLightbox();
  var items = [];
  var currentIndex = -1;

  function syncItems() {
    items = Array.prototype.slice.call(
      document.querySelectorAll(".gallery .image[data-full]")
    );
  }

  function openByIndex(index) {
    if (!items.length) {
      return;
    }
    if (index < 0) {
      index = items.length - 1;
    }
    if (index >= items.length) {
      index = 0;
    }
    currentIndex = index;
    var imageCard = items[currentIndex];
    var full = imageCard.getAttribute("data-full");
    if (!full) {
      return;
    }
    var titleNode = imageCard.querySelector("p");
    var title = titleNode ? titleNode.textContent.trim() : "";
    openLightbox(full, title);
  }

  function openLightbox(src, alt) {
    lb.image.src = src;
    lb.image.alt = alt || "";
    lb.overlay.classList.add("is-open");
    document.documentElement.style.overflow = "hidden";
  }

  function closeLightbox() {
    lb.overlay.classList.remove("is-open");
    lb.image.removeAttribute("src");
    lb.image.alt = "";
    currentIndex = -1;
    document.documentElement.style.overflow = "";
  }

  function showPrev() {
    if (currentIndex === -1) {
      return;
    }
    openByIndex(currentIndex - 1);
  }

  function showNext() {
    if (currentIndex === -1) {
      return;
    }
    openByIndex(currentIndex + 1);
  }

  document.addEventListener("click", function (event) {
    var target = event.target;

    var imageCard = target.closest(".image[data-full]");
    if (imageCard) {
      event.preventDefault();
      syncItems();
      var index = items.indexOf(imageCard);
      if (index === -1) {
        return;
      }
      openByIndex(index);
      return;
    }

    if (target === lb.overlay || target.closest(".gallery-lightbox__close")) {
      closeLightbox();
      return;
    }

    if (target.closest(".gallery-lightbox__nav--prev")) {
      showPrev();
      return;
    }

    if (target.closest(".gallery-lightbox__nav--next")) {
      showNext();
    }
  });

  document.addEventListener("keydown", function (event) {
    if (!lb.overlay.classList.contains("is-open")) {
      return;
    }
    if (event.key === "Escape") {
      closeLightbox();
      return;
    }
    if (event.key === "ArrowLeft") {
      showPrev();
      return;
    }
    if (event.key === "ArrowRight") {
      showNext();
    }
  });
})();
