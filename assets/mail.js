//VALIDACION MAIL
const contactForm = document.getElementById("contactForm");
const inputs = Array.from(
  contactForm.querySelectorAll(".form-control input, .form-control textarea")
);

contactForm.addEventListener("submit", function (event) {
  if (!validateForm()) {
    event.preventDefault();
  }
});

inputs.forEach((input) => {
  input.addEventListener("input", function () {
    validateInput(input);
  });

  input.addEventListener("blur", function () {
    validateInput(input);
  });
});

function validateForm() {
  return inputs.every(validateInput);
}

function validateInput(input) {
  const formControl = input.parentElement;
  const value = input.value.trim();
  const isValid = validateValue(input, value);

  if (isValid) {
    showSuccess(formControl);
  } else {
    showError(formControl, getErrorMessage(input));
  }

  return isValid;
}

function validateValue(input, value) {
  if (input.id === "name" || input.id === "lastname") {
    return value !== "" && !/\d/.test(value);
  }

  if (input.id === "email") {
    return isValidEmail(value);
  }

  if (input.id === "phone") {
    return isValidPhone(value);
  }

  if (input.id === "comments") {
    return value.length >= 20;
  }

  return true;
}

function getErrorMessage(input) {
  if (input.id === "name" || input.id === "lastname") {
    return "El campo es obligatorio y no debe contener números.";
  }

  if (input.id === "email") {
    return "Ingrese un email válido.";
  }

  if (input.id === "phone") {
    return "Ingrese un número de teléfono válido.";
  }

  if (input.id === "comments") {
    return "El mensaje debe tener al menos 20 caracteres.";
  }

  return "";
}

function showError(formControl, message) {
  formControl.classList.add("error");
  formControl.classList.remove("success");
  formControl.querySelector("small").textContent = message;
}

function showSuccess(formControl) {
  formControl.classList.remove("error");
  formControl.classList.add("success");
  formControl.querySelector("small").textContent = "";
}

function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

function isValidPhone(phone) {
  const phoneRegex = /^\d+$/;
  return phoneRegex.test(phone);
}

$(document).ready(function () {
  // Escucha el evento de envío del formulario
  $("form").on("submit", function (e) {
    e.preventDefault(); // Evita el envío normal del formulario

    // Validate the form before submitting
    if (!validateForm()) {
      // Show an error message or handle the error as you prefer
      console.log(
        "Form validation failed. Please fill out all required fields correctly."
      );
      return;
    }

    // Envía la solicitud AJAX al archivo PHP
    $.ajax({
      type: "POST",
      url: "assets/mail.php",
      data: $(this).serialize(), // Envía los datos del formulario
      success: function (response) {
        // Muestra la alerta de SweetAlert
        swal({
          title: "¡Mensaje enviado!",
          text: "Tu mensaje se ha enviado correctamente.",
          icon: "success",
          button: "Aceptar",
        });

        // Restablece el formulario
        $("form")[0].reset();
      },
      error: function (xhr, status, error) {
        // Handle the error if the AJAX request fails
        console.log("Error submitting the form: ", error);
      },
    });
  });
});
