(function () {
  const slider = document.querySelector(".js-reviews-slider");
  if (!slider) {
    return;
  }

  const cards = Array.from(slider.querySelectorAll(".review-card"));
  const prevButton = slider.querySelector(".reviews-slider__nav--prev");
  const nextButton = slider.querySelector(".reviews-slider__nav--next");

  if (cards.length === 0 || !prevButton || !nextButton) {
    return;
  }

  const mobileQuery = window.matchMedia("(max-width: 640px)");
  let startIndex = 0;

  function getVisibleCount() {
    return mobileQuery.matches ? 1 : 3;
  }

  function render() {
    const visibleCount = Math.min(getVisibleCount(), cards.length);
    const visibleIndices = new Set();

    for (let i = 0; i < visibleCount; i += 1) {
      visibleIndices.add((startIndex + i) % cards.length);
    }

    cards.forEach((card, index) => {
      card.hidden = !visibleIndices.has(index);
    });
  }

  function move(step) {
    startIndex = (startIndex + step + cards.length) % cards.length;
    render();
  }

  prevButton.addEventListener("click", function () {
    move(-1);
  });

  nextButton.addEventListener("click", function () {
    move(1);
  });

  if (typeof mobileQuery.addEventListener === "function") {
    mobileQuery.addEventListener("change", render);
  } else if (typeof mobileQuery.addListener === "function") {
    mobileQuery.addListener(render);
  }

  render();
})();
