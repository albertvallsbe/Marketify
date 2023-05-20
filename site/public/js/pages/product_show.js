import "../app.js";

let currentSlide = 0;
const slides = document.querySelectorAll(".slider .slide");
const buttons = document.querySelectorAll(".slider-buttons .slide-button");

buttons.forEach((button) => {
    button.addEventListener("click", function () {
        const slideIndex = parseInt(this.dataset.slideIndex);
        changeSlide(slideIndex);
    });
});

function changeSlide(n) {
    hideSlide(currentSlide);
    currentSlide = n;
    showSlide(currentSlide);
    updateActiveButton(currentSlide);
}

function showSlide(index) {
    slides[index].style.display = "block";
}

function hideSlide(index) {
    slides[index].style.display = "none";
}

function updateActiveButton(index) {
    buttons.forEach((button) => {
        button.classList.remove("active");
    });
    buttons[index].classList.add("active");
}

showSlide(currentSlide);
