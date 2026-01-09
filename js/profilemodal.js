const profileImg = document.getElementById("profileImg");
const profileModal = document.getElementById("profileModal");
const closeProfile = document.querySelector(".close-profile");
const profileInput = document.getElementById("profileInput");
const previewImg = document.getElementById("previewImg");
const saveProfileBtn = document.getElementById("saveProfileBtn");
const cancelProfileBtn = document.getElementById("cancelProfileBtn");

profileImg.addEventListener("click", () => {
    profileModal.style.display = "flex";
    previewImg.src = profileImg.src;
});
function closeModal() {
    profileModal.style.display = "none";
    profileInput.value = "";
    previewImg.src = profileImg.src;
}

closeProfile.onclick = closeModal;
cancelProfileBtn.onclick = closeModal;

profileInput.addEventListener("change", () => {
    if (profileInput.files[0]) {
        previewImg.src = URL.createObjectURL(profileInput.files[0]);
    }
});

saveProfileBtn.addEventListener("click", () => {
    if (!profileInput.files.length) return;

    const formData = new FormData();
    formData.append("profile", profileInput.files[0]);

    fetch("../backend/upload_profile.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            profileImg.src = "../uploads/profile/" + data.image;
            closeModal();
        } else {
            alert(data.msg);
        }
    })
    .catch(err => console.error(err));
});