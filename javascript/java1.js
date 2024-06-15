const username = "admin";
const password = "password";
const login_success = document.getElementById("success");
const login_failure = document.getElementById("failure");

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById("myForm");

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        // CAPTURING THE USER INPUT VALUES FROM THE FORM
        const Uname = document.getElementById("username").value.trim();
        const Upass = document.getElementById("Upassword").value.trim();

        // VALIDATION
        if (Uname === "") {
            alert("Fill in the username");
            return;  
        }

        if (Upass === "") {
            alert("Fill in the password");
            return; 
        }

        // CREDENTIAL CHECKING
        if (Uname === username && Upass === password) {
            // Clear previous failure message and show success message
            login_failure.style.display = "none";  // Hide failure message
            login_success.style.display = "block";  // Show success message
            login_success.innerHTML = "Login successful! Redirecting...";

            setTimeout(() => {
                window.location.href = "dashboard.php";  // Replace with your target URL
            }, 3000);
        } else {
            // Show failure message
            login_success.style.display = "none";  // Hide success message
            login_failure.style.display = "block";  // Show failure message
            login_failure.innerHTML = "Incorrect username or password";
        }
    });
});
