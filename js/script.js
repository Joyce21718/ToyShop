const buyNowButtons = document.querySelectorAll(".toy-card button");
const modal = document.getElementById("buyModal");
const closeBtn = modal.querySelector(".close");
const modalToyImage = document.getElementById("modalToyImage");
const registerSection = document.getElementById("registerSection");
const registerForm = document.getElementById("registerForm");

// Show modal with toy image
buyNowButtons.forEach(btn => {
    btn.addEventListener("click", () => {
        const card = btn.closest(".toy-card");
        const img = card.querySelector("img");
        modalToyImage.src = img.getAttribute("src");
        registerSection.style.display = "block";
        modal.style.display = "block";
    });
});

// Close modal
closeBtn.onclick = () => modal.style.display = "none";
window.onclick = e => { if (e.target === modal) modal.style.display = "none"; };

// Handle registration
registerForm.addEventListener("submit", e => {
    e.preventDefault();

    const name = document.getElementById("regName").value.trim();
    const email = document.getElementById("regEmail").value.trim();
    const phone = document.getElementById("regPhone").value.trim();
    const address = document.getElementById("regAddress").value.trim();
    const password = document.getElementById("regPassword").value;
    const confirmPassword = document.getElementById("regConfirmPassword").value;

    if (password !== confirmPassword) {
        alert("Passwords do not match!");
        return;
    }

    const formData = new FormData();
    formData.append("regName", name);
    formData.append("regEmail", email);
    formData.append("regPhone", phone);
    formData.append("regAddress", address);
    formData.append("regPassword", password);
    formData.append("regConfirmPassword", confirmPassword);

    const submitBtn = registerForm.querySelector("button[type='submit']");
    submitBtn.disabled = true;

    fetch("../backend/register.php", { method: "POST", body: formData })
        .then(res => res.text()) 
        .then(data => {
            try {
                const json = JSON.parse(data);
                alert(json.message);
                if (json.status === "success") {
                    registerForm.reset();
                    modal.style.display = "none";
                    window.location.href = "login.html";
                }
            } catch (err) {
                console.error("Invalid JSON from server:", data);
                alert("Server error. Check console for details.");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Something went wrong. Try again.");
        })
        .finally(() => submitBtn.disabled = false);
});
