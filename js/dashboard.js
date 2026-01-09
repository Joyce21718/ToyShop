document.addEventListener("DOMContentLoaded", () => {

    document.getElementById("userName").textContent = USER_NAME;
    document.getElementById("navUserName").textContent = USER_NAME;
    const buyButtons = document.querySelectorAll(".buy-now-btn");
    const modal = document.getElementById("buyModal");
    const closeBtn = modal.querySelector(".close");
    const modalImage = document.getElementById("modalImage");
    const modalToyName = document.getElementById("modalToyName");
    const modalToyPrice = document.getElementById("modalToyPrice");
    const quantityInput = document.getElementById("quantity");
    const minusQty = document.getElementById("minusQty");
    const plusQty = document.getElementById("plusQty");
    const addressInput = document.getElementById("address");
    const confirmBuy = document.getElementById("confirmBuy");
    buyButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            const card = btn.closest(".toy-card");

            modalToyName.textContent = card.dataset.name;
            modalToyPrice.textContent = card.dataset.price;
            modalImage.src = card.querySelector("img").src;

            quantityInput.value = 1;
            addressInput.value = "";
            document.querySelector('input[name="payment"][value="cod"]').checked = true;

            modal.style.display = "flex";
        });
    });
    closeBtn.onclick = () => modal.style.display = "none";
    window.onclick = e => {
        if (e.target === modal) modal.style.display = "none";
    };

    plusQty.onclick = () => quantityInput.value++;
    minusQty.onclick = () => {
        if (quantityInput.value > 1) quantityInput.value--;
    };
    confirmBuy.onclick = () => {
        const address = addressInput.value.trim();
        const payment = document.querySelector('input[name="payment"]:checked').value;

        if (!address) {
            alert("Please enter your shipping address.");
            return;
        }

        fetch("../backend/save_order.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                user_id: USER_ID,
                name: modalToyName.textContent,
                price: modalToyPrice.textContent,
                quantity: quantityInput.value,
                image: modalImage.src,
                address: address,
                payment: payment
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                alert("Order placed successfully!");
                modal.style.display = "none";
                location.reload();
            } else {
                alert("Failed to place order. Try again.");
            }
        })
        .catch(err => console.error(err));
    };
});
