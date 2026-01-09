const addCartButtons = document.querySelectorAll(".add-cart-btn");
const cartModal = document.getElementById("cartModal");
const cartClose = cartModal.querySelector(".close");

const cartToyName = document.getElementById("cartToyName");
const cartToyPrice = document.getElementById("cartToyPrice");
const cartToyImage = document.getElementById("cartToyImage");
const cartQuantity = document.getElementById("cartQuantity");
const cartMinusQty = document.getElementById("cartMinusQty");
const cartPlusQty = document.getElementById("cartPlusQty");

addCartButtons.forEach(btn => {
    btn.addEventListener("click", e => {
        const card = e.target.closest(".toy-card");
        const name = card.dataset.name;
        const price = card.dataset.price;
        const imgSrc = card.querySelector("img").src;

        cartToyName.value = name;
        cartToyPrice.value = price;
        cartToyImage.src = imgSrc;
        cartQuantity.value = 1;

        cartModal.style.display = "block";
    });
});

cartClose.addEventListener("click", () => {
    cartModal.style.display = "none";
});

cartMinusQty.addEventListener("click", () => {
    if (cartQuantity.value > 1) cartQuantity.value--;
});

cartPlusQty.addEventListener("click", () => {
    cartQuantity.value++;
});

document.getElementById("cartForm").addEventListener("submit", e => {
    e.preventDefault();
    const item = {
        name: cartToyName.value,
        price: cartToyPrice.value,
        quantity: parseInt(cartQuantity.value),
        img: cartToyImage.src
    };

    fetch("../backend/addtocart.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(item)
    }).then(() => {
        alert(`${item.name} added to cart!`);
        cartModal.style.display = "none";
    });
});
