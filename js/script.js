// Toggle mobile menu when the DOM is fully loaded
document.addEventListener("DOMContentLoaded", function () {
	const toggleButton = document.querySelector(".navbar .mobile-menu-toggle");
	const mobileMenu = document.querySelector(".navbar .mobile-menu-items");

	// Event listener for menu toggle button
	toggleButton.addEventListener("click", function () {
		mobileMenu.classList.toggle("active"); // Toggle the active class
		const isActive = mobileMenu.classList.contains("active");
		toggleButton.setAttribute("aria-expanded", isActive); // Update aria for accessibility
	});

	// Close the mobile menu when any link is clicked
	mobileMenu.addEventListener("click", function (e) {
		if (e.target.tagName === "A") {
			// Check if the clicked element is a link
			mobileMenu.classList.remove("active"); // Remove the active class
			toggleButton.setAttribute("aria-expanded", "false"); // Update aria for accessibility
		}
	});
});

// Carousel
let currentIndex = 0;

function moveSlide(direction) {
	const slides = document.querySelectorAll(".carousel-item");
	slides[currentIndex].classList.remove("active");

	currentIndex += direction;

	if (currentIndex < 0) {
		currentIndex = slides.length - 1;
	} else if (currentIndex >= slides.length) {
		currentIndex = 0;
	}

	slides[currentIndex].classList.add("active");
	const offset = -currentIndex * 100; // Move slides
	document.querySelector(
		".carousel-inner"
	).style.transform = `translateX(${offset}%)`;
}

// Optional: Auto transition every 5 seconds
setInterval(() => moveSlide(1), 5000);
