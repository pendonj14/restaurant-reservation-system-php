// scripts.js
function validateForm() {
    var name = document.getElementById('name').value;
    var phone = document.getElementById('phone').value;
    var email = document.getElementById('email').value;
    var tables = document.getElementById('tables').value;
    var date = document.getElementById('date').value;
    var time = document.getElementById('time').value;

    if (!name || !phone || !email || !tables || !date || !time) {
        alert("Please fill out all fields.");
        return false;
    }
    return true;
}
