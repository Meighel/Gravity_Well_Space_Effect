document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("register-form");

    const firstname = document.getElementById("firstname");
    const middlename = document.getElementById("middlename");
    const lastname = document.getElementById("lastname");
    const birthday = document.getElementById("birthday");
    const email = document.getElementById("email");
    const mobile = document.getElementById("mobile");
    const password = document.getElementById("password");

    const showError = (input, message) => {
        let error = input.nextElementSibling;
        if (!error || !error.classList.contains("text-danger")) {
            error = document.createElement("div");
            error.className = "text-danger";
            input.parentNode.appendChild(error);
        }
        error.textContent = message;
    };

    const clearError = (input) => {
        let error = input.nextElementSibling;
        if (error && error.classList.contains("text-danger")) {
            error.textContent = "";
        }
    };

    const validateName = (input) => {
        const nameRegex = /^[A-Za-z][A-Za-z\s]{1,}$/;
        if (!nameRegex.test(input.value.trim())) {
            showError(input, "Must be at least 2 letters, no numbers or symbols.");
            return false;
        }
        clearError(input);
        return true;
    };

    const validateBirthday = () => {
        const birthDate = new Date(birthday.value);
        const minDate = new Date("2013-01-01");
        if (birthDate > minDate) {
            showError(birthday, "Birthday must be before 2013.");
            return false;
        }
        clearError(birthday);
        return true;
    };

    const validateEmail = () => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value.trim())) {
            showError(email, "Enter a valid email address.");
            return false;
        }
        clearError(email);
        return true;
    };

    const validateMobile = () => {
        const mobileRegex = /^0\d{10}$/;
        if (!mobileRegex.test(mobile.value.trim())) {
            showError(mobile, "Mobile must start with 0 and be 11 digits.");
            return false;
        }
        clearError(mobile);
        return true;
    };

    const validatePassword = () => {
        const pass = password.value;
        const passRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
        if (!passRegex.test(pass)) {
            showError(password, "Min 8 chars, 1 capital, 1 number, 1 special symbol.");
            return false;
        }
        clearError(password);
        return true;
    };

    firstname.addEventListener("input", () => validateName(firstname));
    middlename.addEventListener("input", () => {
        if (middlename.value.trim() === "") {
            clearError(middlename); 
            return true;
        }
        return validateName(middlename);
    });
    lastname.addEventListener("input", () => validateName(lastname));
    birthday.addEventListener("change", validateBirthday);
    email.addEventListener("input", validateEmail);
    mobile.addEventListener("input", validateMobile);
    password.addEventListener("input", validatePassword);

    form.addEventListener("submit", function (e) {
        const valid =
            validateName(firstname) &&
            (middlename.value.trim() === "" || validateName(middlename)) &&
            validateName(lastname) &&
            validateBirthday() &&
            validateEmail() &&
            validateMobile() &&
            validatePassword();

        if (!valid) {
            e.preventDefault(); 
        }
    });
});
