const loginForm = document.getElementById("loginForm");
const showPassword = document.getElementById("showPassword");
const passwordInput = document.getElementById("password");

showPassword.addEventListener("change", () => {
    passwordInput.type = showPassword.checked ? "text" : "password";
});

loginForm.addEventListener("submit", e => {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const password = passwordInput.value;
    const formData = new FormData();
    formData.append("email", email);
    formData.append("password", password);
    fetch("../backend/login.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            window.location.href = "dashboard.php";
        }
    })
    .catch(err => {
        console.error(err);
        alert("Something went wrong. Please try again.");
    });
});
