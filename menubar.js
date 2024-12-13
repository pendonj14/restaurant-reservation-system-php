function toggleDropdown(event) {
    event.stopPropagation(); // Prevents the click event from closing the dropdown immediately
    
    var dropdown = document.getElementById("dropdown");
    var button = event.target; // The button that was clicked
  
    // Get the button's position
    var buttonRect = button.getBoundingClientRect();
    var dropdownHeight = dropdown.offsetHeight;
  
    // Position the dropdown directly below the button
    dropdown.style.left = buttonRect.left + 'px';  // Align with the left of the button
    dropdown.style.top = (buttonRect.top + buttonRect.height) + 'px';  // Position below the button
  
    // Toggle visibility of the dropdown
    dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
  }
  
  // Close the dropdown if clicked outside of it
  window.onclick = function(event) {
    if (!event.target.matches('.menu-icon-button, .menu-icon-button i')) {
      var dropdown = document.getElementById("dropdown");
      if (dropdown.style.display === "block") {
        dropdown.style.display = "none";
      }
    }
  }
  