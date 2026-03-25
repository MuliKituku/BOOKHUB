  const slideContainer = document.getElementById("slide-container");
  const slides = slideContainer.children;
  const totalSlides = slides.length;

  let currentIndex = 0;

  function updateSlide() {
    slideContainer.style.transform = `translateX(-${currentIndex * 100}%)`;
  }

  // Auto slide every 4 seconds
  setInterval(() => {
    currentIndex = (currentIndex + 1) % totalSlides;
    updateSlide();
  }, 4000);

  // Manual buttons
  document.getElementById("prev").addEventListener("click", () => {
    currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
    updateSlide();
  });

  document.getElementById("next").addEventListener("click", () => {
    currentIndex = (currentIndex + 1) % totalSlides;
    updateSlide();
  });
