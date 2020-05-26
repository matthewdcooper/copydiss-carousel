// Cycle through carousel images.
const carousel = {
    current: 2,
    set: (i) => {
      document.getElementById("copydiss-carousel-" + i).checked = true;
    }
  };
  
  window.onload = () => {
    setInterval(() => {
      carousel.current += 1;
      if (carousel.current > 3) carousel.current = 1;
      carousel.set(carousel.current);
    }, 200);
  };