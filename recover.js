document.getElementById("recoverForm").addEventListener("submit", function (e) {
    const username = document.querySelector('input[name="username"]').value.trim();
    const phonenumber = document.querySelector('input[name="phonenumber"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();

    if (!username || !phonenumber || !email) {
        e.preventDefault();
        alert("All fields are required!");
    }
});
