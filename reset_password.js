// reset_password.js
document.getElementById("resetForm").addEventListener("submit", function (e) {
    const password = document.querySelector('input[name="password"]').value.trim();

    if (password.length < 6) {
        e.preventDefault(); // Stop form submission
        alert("Password must be at least 6 characters long.");
    }
});
