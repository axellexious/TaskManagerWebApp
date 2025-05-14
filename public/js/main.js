document.addEventListener("DOMContentLoaded", function () {
  // Auto-close alerts after 5 seconds
  setTimeout(function () {
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach(function (alert) {
      // Create a new bootstrap alert and close it
      if (bootstrap && bootstrap.Alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      }
    });
  }, 5000);

  // Password strength validator
  const passwordField = document.getElementById("password");
  const confirmPasswordField = document.getElementById("confirm_password");

  if (passwordField && confirmPasswordField) {
    // Check password match
    confirmPasswordField.addEventListener("input", function () {
      if (confirmPasswordField.value === passwordField.value) {
        confirmPasswordField.classList.remove("is-invalid");
        confirmPasswordField.classList.add("is-valid");
      } else {
        confirmPasswordField.classList.remove("is-valid");
        confirmPasswordField.classList.add("is-invalid");
      }
    });

    // Check password strength
    passwordField.addEventListener("input", function () {
      const password = passwordField.value;

      // Simple password strength check
      if (password.length < 6) {
        passwordField.classList.remove("is-valid");
        passwordField.classList.add("is-invalid");
      } else {
        passwordField.classList.remove("is-invalid");
        passwordField.classList.add("is-valid");
      }

      // Clear confirm password validation when password changes
      confirmPasswordField.classList.remove("is-valid", "is-invalid");
    });
  }

  // Enable Bootstrap tooltips
  try {
    const tooltipTriggerList = [].slice.call(
      document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    if (bootstrap && bootstrap.Tooltip) {
      tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    }
  } catch (e) {
    console.error("Error initializing tooltips:", e);
  }

  // Dark mode toggle functionality
  const darkModeToggle = document.getElementById("darkModeToggle");
  const darkModeIcon = document.getElementById("darkModeIcon");

  // Debug dark mode elements
  console.log("Dark mode toggle element:", darkModeToggle);
  console.log("Dark mode icon element:", darkModeIcon);

  // Apply dark mode if it was enabled before
  if (localStorage.getItem("darkMode") === "enabled") {
    console.log("Dark mode was previously enabled, applying...");
    document.body.classList.add("dark-mode");
    if (darkModeIcon) {
      darkModeIcon.classList.remove("bi-moon");
      darkModeIcon.classList.add("bi-sun");
    }
  }

  // Toggle dark mode on click
  if (darkModeToggle) {
    darkModeToggle.addEventListener("click", function (e) {
      console.log("Dark mode toggle clicked");
      e.preventDefault(); // Prevent default link behavior

      // Toggle dark mode class on body
      document.body.classList.toggle("dark-mode");

      // Update icon
      if (darkModeIcon) {
        if (document.body.classList.contains("dark-mode")) {
          darkModeIcon.classList.remove("bi-moon");
          darkModeIcon.classList.add("bi-sun");
          localStorage.setItem("darkMode", "enabled");
          console.log("Dark mode enabled");
        } else {
          darkModeIcon.classList.remove("bi-sun");
          darkModeIcon.classList.add("bi-moon");
          localStorage.setItem("darkMode", "disabled");
          console.log("Dark mode disabled");
        }
      }
    });
  } else {
    console.warn("Dark mode toggle button not found in the DOM");
  }

  // Debug function to manually toggle dark mode (can be triggered from console)
  window.toggleDarkMode = function () {
    document.body.classList.toggle("dark-mode");
    console.log(
      "Dark mode manually toggled:",
      document.body.classList.contains("dark-mode")
    );
  };
});
