document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".cancel-btn").forEach(btn => {
        btn.onclick = () => {
            if (!confirm("Cancel this order?")) return;

            fetch("../backend/cancel_order.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ order_id: btn.dataset.id })
            })
            .then(res => res.json())
            .then(() => location.reload());
        };
    });

    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.onclick = () => {
            if (!confirm("Delete this order permanently?")) return;

            fetch("../backend/delete_order.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ order_id: btn.dataset.id })
            })
            .then(res => res.json())
            .then(() => location.reload());
        };
    });

});
