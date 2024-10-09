/* end of effects on side bars */

/* Effects on image carousel */
let slideIndex = 0;
const slides = document.querySelectorAll('.carousel-slide');

document.querySelector('.carousel-prev').addEventListener('click', () => {
    slideIndex = (slideIndex > 0) ? slideIndex - 1 : slides.length - 1;
    updateCarousel();
});

document.querySelector('.carousel-next').addEventListener('click', () => {
    slideIndex = (slideIndex < slides.length - 1) ? slideIndex + 1 : 0;
    updateCarousel();
});

function updateCarousel() {
    const offset = -slideIndex * 100; // Assuming each slide is 100% width
    slides.forEach(slide => {
        slide.style.transform = `translateX(${offset}%)`;
    });
}
/* End of effects on image carousel */

function navigateToLink() {
    var dropdown = document.getElementById("linkDropdown");
    var selectedValue = dropdown.value;

    // Only navigate if a valid link is selected
    if (selectedValue) {
        window.location.href = selectedValue;
    }
}