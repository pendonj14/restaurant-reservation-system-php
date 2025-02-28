// Modal Control
const modal = document.getElementById("modal");
const modalMessage = document.getElementById("modalMessage");
const closeModal = document.getElementById("closeModal");

function showModal(message) {
    modalMessage.textContent = message; // Set modal content
    modal.style.display = "block"; // Show modal
}

closeModal.addEventListener("click", () => {
    modal.style.display = "none"; // Close modal
});

window.addEventListener("click", (event) => {
    if (event.target === modal) {
        modal.style.display = "none"; // Close when clicking outside
    }
});

// Form Submission with AJAX
document.getElementById("registerForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent default form submission

    const formData = new FormData(this); // Gather form data

    // AJAX request
    fetch("register.php", {
        method: "POST",
        body: formData,
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.json()) // Parse JSON response
        .then((data) => {
            if (data.success) {
                showModal(data.message); // Show success modal
                this.reset(); // Clear form
            } else {
                showModal(data.message); // Show error modal
            }
        })
        .catch((error) => {
            showModal("An unexpected error occurred. Please try again.");
            console.error("Error:", error);
        });
});
