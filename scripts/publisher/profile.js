function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById("profilePicturePreview");

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);

        const formData = new FormData();
        formData.append('profilePicture', input.files[0]);

        fetch("/PenaconyExchange/db/backend/publisher/updateProfileLogo.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.text()) 
        .then(text => {
            console.log("Raw response:", text);
            try {
                const json = JSON.parse(text);
                if (json.success) {
                    console.log("Success");
                } else {
                    console.error("Server error:", json.message);
                }
            } catch (e) {
                console.error("JSON parse failed", e);
            }
        })
        .catch(err => {
            console.error("Upload failed", err);
        });
    }
}

// Toggle between forms
function toggleSection(id) {
    const allForms = document.querySelectorAll(".formContainer");
    allForms.forEach(form => form.classList.add("hidden"));

    const target = document.getElementById(id);
    if (target) {
        target.classList.remove("hidden");
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const formContainer = document.getElementById("usernameForm");
    const overlay = document.getElementById("usernameModalOverlay");

    // Show modal on button click
    document.querySelector("button[onclick=\"toggleSection('usernameForm')\"]").addEventListener("click", () => {
        formContainer.classList.remove("hidden");
        overlay.classList.remove("hidden");
    });

    // Hide modal when clicking the dark overlay
    overlay.addEventListener("click", () => {
        overlay.classList.add("hidden");
        formContainer.classList.add("hidden");
    });

    // Prevent click inside modal box from closing
    document.querySelector(".modal-box").addEventListener("click", (event) => {
        event.stopPropagation();
    });
});

// Handle Username Form
document.querySelector("#usernameForm form").addEventListener("submit", async function (e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    const response = await fetch(form.action, {
        method: "POST",
        body: formData
    });

    const result = await response.text();
    alert(result);

    const newUsername = form.querySelector("input[name='username']").value;
    document.getElementById("username").textContent = "Username: " + newUsername;
    toggleSection(""); // hide form
});


document.addEventListener("DOMContentLoaded", () => {
    const formContainer = document.getElementById("emailForm");
    const overlay = document.getElementById("emailModalOverlay");

    // Show modal on button click
    document.querySelector("button[onclick=\"toggleSection('emailForm')\"]").addEventListener("click", () => {
        formContainer.classList.remove("hidden");
        overlay.classList.remove("hidden");
    });

    // Hide modal when clicking the dark overlay
    overlay.addEventListener("click", () => {
        overlay.classList.add("hidden");
        formContainer.classList.add("hidden");
    });

    // Prevent click inside modal box from closing
    document.querySelector(".modal-box").addEventListener("click", (event) => {
        event.stopPropagation();
    });
});

// Handle Email Form
document.querySelector("#emailForm form").addEventListener("submit", async function (e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    const response = await fetch(form.action, {
        method: "POST",
        body: formData
    });

    const result = await response.text();
    alert(result);

    const newEmail = form.querySelector("input[name='email']").value;
    document.getElementById("email").textContent = "Email: " + newEmail;
    toggleSection(""); // hide form
});


document.addEventListener("DOMContentLoaded", () => {
    const formContainer = document.getElementById("passwordForm");
    const overlay = document.getElementById("passwordModalOverlay");

    // Show modal on button click
    document.querySelector("button[onclick=\"toggleSection('passwordForm')\"]").addEventListener("click", () => {
        formContainer.classList.remove("hidden");
        overlay.classList.remove("hidden");
    });

    // Hide modal when clicking the dark overlay
    overlay.addEventListener("click", () => {
        overlay.classList.add("hidden");
        formContainer.classList.add("hidden");
    });

    // Prevent click inside modal box from closing
    document.querySelector(".modal-box").addEventListener("click", (event) => {
        event.stopPropagation();
    });
});

// Handle Password Form
document.querySelector("#passwordForm form").addEventListener("submit", async function (e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    const response = await fetch(form.action, {
        method: "POST",
        body: formData
    });

    const result = await response.text();
    alert(result);

    const newPassword = form.querySelector("input[name='newPassword']").value;
    document.getElementById("password").textContent = "Password: " + newPassword;
    toggleSection(""); // hide form
});
