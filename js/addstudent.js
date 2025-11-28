const form = document.getElementById("studentForm");

const id = document.getElementById("ID");
const lastName = document.getElementById("LName");
const firstName = document.getElementById("FName");
const email = document.getElementById("Email");

const idError = document.getElementById("idError");
const lastNameError = document.getElementById("LNameError");
const firstNameError = document.getElementById("FNameError");
const emailError = document.getElementById("emailError");

function validateId() {
  idError.textContent = "";
  if (id.value.trim() === "") {
    idError.textContent = "Student ID is required.";
    return false;
  } else if (!/^\d+$/.test(id.value)) {
    idError.textContent = "Student ID must contain only numbers.";
    return false;
  }
  return true;
}

function validateLastName() {
  lastNameError.textContent = "";
  if (lastName.value.trim() === "") {
    lastNameError.textContent = "Last Name is required.";
    return false;
  } else if (!/^[A-Za-z]+$/.test(lastName.value)) {
    lastNameError.textContent = "Last Name must contain only letters.";
    return false;
  }
  return true;
}

function validateFirstName() {
  firstNameError.textContent = "";
  if (firstName.value.trim() === "") {
    firstNameError.textContent = "First Name is required.";
    return false;
  } else if (!/^[A-Za-z]+$/.test(firstName.value)) {
    firstNameError.textContent = "First Name must contain only letters.";
    return false;
  }
  return true;
}

function validateEmail() {
  emailError.textContent = "";
  if (email.value.trim() === "") {
    emailError.textContent = "Email is required.";
    return false;
  } else if (!/^[\w.-]+@[A-Za-z\d.-]+\.[A-Za-z]{2,}$/.test(email.value)) {
    emailError.textContent = "Invalid email format.";
    return false;
  }
  return true;
}


form.addEventListener("submit", function (e) {
  

  if (!validateId() || !validateLastName() || !validateFirstName() || !validateEmail()) return;

  const formData = new FormData(form);

  fetch("add_Student.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      responseBox.textContent = data.message;
      responseBox.style.color = data.success ? "green" : "red";

      if (!data.success) return; 

      const idValue = id.value.trim();
      const lastValue = lastName.value.trim();
      const firstValue = firstName.value.trim();

      const newRow = tbody.insertRow();

      newRow.insertCell().textContent = idValue;
      newRow.insertCell().textContent = lastValue;
      newRow.insertCell().textContent = firstValue;

      for (let i = 0; i < 6; i++) {
        const presenceCell = newRow.insertCell();
        presenceCell.classList.add("presence");
        const presenceInput = document.createElement("input");
        presenceInput.type = "checkbox";
        presenceCell.appendChild(presenceInput);

        const participationCell = newRow.insertCell();
        participationCell.classList.add("participation");
        const participationInput = document.createElement("input");
        participationInput.type = "checkbox";
        participationCell.appendChild(participationInput);
      }

      const absCell = newRow.insertCell(); absCell.classList.add("abs");
      const parCell = newRow.insertCell(); parCell.classList.add("parNbr");
      const msgCell = newRow.insertCell(); msgCell.classList.add("msg");

      updateRow(newRow);

      form.reset();
    })
    .catch(() => {
      responseBox.textContent = "Error connecting to server.";
      responseBox.style.color = "red";
    });
});