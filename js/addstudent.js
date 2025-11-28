const form = document.getElementById("studentForm");
const responseBox = document.getElementById("responseMessage"); // matches your HTML div

// Input fields
const id = document.getElementById("ID");
const lastName = document.getElementById("LName");
const firstName = document.getElementById("FName");
const email = document.getElementById("Email");

// Error spans
const idError = document.getElementById("idError");
const lastNameError = document.getElementById("LNameError");
const firstNameError = document.getElementById("FNameError");
const emailError = document.getElementById("emailError");

// Validation functions
function validateId() {
    idError.textContent = "";
    if (id.value.trim() === "") { idError.textContent = "Student ID is required."; return false; }
    if (!/^\d+$/.test(id.value)) { idError.textContent = "Student ID must be numbers only."; return false; }
    return true;
}

function validateLastName() {
    lastNameError.textContent = "";
    if (lastName.value.trim() === "") { lastNameError.textContent = "Last Name is required."; return false; }
    if (!/^[A-Za-z]+$/.test(lastName.value)) { lastNameError.textContent = "Last Name must contain letters only."; return false; }
    return true;
}

function validateFirstName() {
    firstNameError.textContent = "";
    if (firstName.value.trim() === "") { firstNameError.textContent = "First Name is required."; return false; }
    if (!/^[A-Za-z]+$/.test(firstName.value)) { firstNameError.textContent = "First Name must contain letters only."; return false; }
    return true;
}

function validateEmail() {
    emailError.textContent = "";
    if (email.value.trim() === "") { emailError.textContent = "Email is required."; return false; }
    if (!/^[\w.-]+@[A-Za-z\d.-]+\.[A-Za-z]{2,}$/.test(email.value)) { emailError.textContent = "Invalid email format."; return false; }
    return true;
}

// Form submit handler
form.addEventListener("submit", function (e) {
    e.preventDefault(); // prevent default submission

    // Validate inputs
    if (!validateId() || !validateLastName() || !validateFirstName() || !validateEmail()) return;

    const formData = new FormData(form);

    fetch("add_student.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        responseBox.textContent = data.message;
        responseBox.style.color = data.success ? "green" : "red";

        if (data.success) {
            form.reset();
        }
    })
    .catch(err => {
        responseBox.textContent = "Error connecting to server.";
        responseBox.style.color = "red";
        console.error(err);
    });
});
