function scrollRowRight(container) {
    container.scrollBy({ left: 300, behavior: 'smooth' });
}

function scrollRowLeft(container) {
    container.scrollBy({ left: -300, behavior: 'smooth' });
}

function updateScrollButtons(container) {
    const prevBtn = container.parentElement.querySelector(".prev-btn");
    const nextBtn = container.parentElement.querySelector(".next-btn");

    prevBtn.style.display = container.scrollLeft > 0 ? "block" : "none";

    const maxScrollLeft = container.scrollWidth - container.clientWidth;
    nextBtn.style.display = container.scrollLeft < maxScrollLeft ? "block" : "none";
}

document.addEventListener("DOMContentLoaded", function() {
    const containers = document.querySelectorAll(".product-row");
    containers.forEach(container => {
        updateScrollButtons(container);
        container.addEventListener("scroll", () => updateScrollButtons(container));
    });
});

window.addEventListener("resize", function() {
    const containers = document.querySelectorAll(".product-row");
    containers.forEach(container => updateScrollButtons(container));
}); 