// Task Manager Main JavaScript File

document.addEventListener("DOMContentLoaded", function () {
  // Auto-close alerts after 5 seconds
  setTimeout(function () {
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach(function (alert) {
      // Create a new bootstrap alert and close it
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
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
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Dark mode toggle functionality
  const darkModeToggle = document.getElementById("darkModeToggle");
  const darkModeIcon = document.getElementById("darkModeIcon");

  // Check for saved dark mode preference
  if (localStorage.getItem("darkMode") === "enabled") {
    document.body.classList.add("dark-mode");
    if (darkModeIcon) {
      darkModeIcon.classList.remove("bi-moon");
      darkModeIcon.classList.add("bi-sun");
    }
  }

  // Toggle dark mode on click
  if (darkModeToggle) {
    darkModeToggle.addEventListener("click", function () {
      // Toggle dark mode class on body
      document.body.classList.toggle("dark-mode");

      // Update icon
      if (darkModeIcon) {
        if (document.body.classList.contains("dark-mode")) {
          darkModeIcon.classList.remove("bi-moon");
          darkModeIcon.classList.add("bi-sun");
          localStorage.setItem("darkMode", "enabled");
        } else {
          darkModeIcon.classList.remove("bi-sun");
          darkModeIcon.classList.add("bi-moon");
          localStorage.setItem("darkMode", "disabled");
        }
      }
    });
  }
});
