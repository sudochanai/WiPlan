window.onload = function() {
    var toast = document.getElementById('toast');
    if (toast) {
        toast.classList.add('show');
        setTimeout(function() {
            toast.classList.remove('show');
            toast.classList.add('fade');
            setTimeout(function() {
                toast.remove();
            }, 500); // Wait for fade out animation to complete
        }, 3000); // Display for 3 seconds
    }

    const registerForm = document.querySelector(".register-form");
    const loginForm = document.querySelector(".login-form");
    const createEventForm = document.querySelector(".create-event-form");

    if (registerForm) {
        registerForm.addEventListener("submit", function(event) {
            // Get form values
            const username = registerForm.username.value.trim();
            const password = registerForm.password.value.trim();
            const email = registerForm.email.value.trim();
            const age = registerForm.age.value;
            const privileges = registerForm.privileges.value;

            // Initialize validation status
            let isValid = true;

            // Validate Username
            if (username.length < 3) {
                alert("Username must be at least 3 characters long.");
                isValid = false;
            }

            // Validate Password
            const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;
            if (!passwordPattern.test(password)) {
                alert("Password must be at least 6 characters long and contain both letters and numbers.");
                isValid = false;
            }

            // Validate Email
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                isValid = false;
            }

            // Validate Age
            if (isNaN(age) || age < 18) {
                alert("You must be at least 18 years old.");
                isValid = false;
            }

            // Validate Privileges
            if (!["attendee", "organizer"].includes(privileges)) {
                alert("Please select a valid privilege.");
                isValid = false;
            }

            // Prevent form submission if validation fails
            if (!isValid) {
                event.preventDefault();
            }
        });
    }

    if (loginForm) {
        loginForm.addEventListener("submit", function(event) {
            // Get form values
            const email = loginForm.email.value.trim();
            const password = loginForm.password.value.trim();

            // Initialize validation status
            let isValid = true;

            // Validate Email
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                isValid = false;
            }

            // Prevent form submission if validation fails
            if (!isValid) {
                event.preventDefault();
            }
        });
    }

    if (createEventForm) {
        createEventForm.addEventListener("submit", function(event) {
            // Get form values
            const name = createEventForm.name.value.trim();
            const description = createEventForm.description.value.trim();
            const venue = createEventForm.venue.value.trim();
            const time = createEventForm.time.value;
            const duration = createEventForm.duration.value.trim();
            const mode = createEventForm.mode.value;
            const price = createEventForm.price.value.trim();
            const image = createEventForm.image.value;

            // Initialize validation status
            let isValid = true;

            // Validate Event Name
            if (name.length < 3) {
                alert("Event name must be at least 3 characters long.");
                isValid = false;
            }

            // Validate Description
            if (description.length < 10) {
                alert("Description must be at least 10 characters long.");
                isValid = false;
            }

            // Validate Venue
            if (venue.length < 3) {
                alert("Venue must be at least 3 characters long.");
                isValid = false;
            }

            // Validate Time
            if (!time) {
                alert("Please enter a valid time.");
                isValid = false;
            }

            // Validate Duration
            if (!/^(\d+)\s*(hours?|h)$/.test(duration)) {
                alert("Duration must be a valid number followed by 'hour' or 'hours'.");
                isValid = false;
            }

            // Validate Mode and Price
            if (mode === "paid" && (isNaN(price) || price <= 0)) {
                alert("Please enter a valid price for paid events.");
                isValid = false;
            }

            // Validate Image (Optional)
            if (image) {
                const allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
                if (!allowedExtensions.exec(image)) {
                    alert("Please upload a valid image file (jpg, jpeg, png, gif).");
                    isValid = false;
                }
            }

            // Prevent form submission if validation fails
            if (!isValid) {
                event.preventDefault();
            }
        });
    }
}; 