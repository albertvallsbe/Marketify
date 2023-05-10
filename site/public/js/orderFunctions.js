//Comprova si hi ha una sessió iniciada
const updateOrdersServerStorage = () => {
    try {
        fetch("/auth")
            .then((response) => response.json())
            .then((data) => {
                if (data.authenticated) {
                    addToServerOrder();
                }
            });
    } catch (error) {
        console.error(error);
    }
};

//Puja l'order al servidor
//crida la funció per ruta
function addToServerOrder() {
    try {
        const orders = localStorage.getItem("cart") || "[]";
        fetch("/orders", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({ orders }),
        }).then(() => {});
    } catch (error) {
        console.error(error);
    }
}

export { updateOrdersServerStorage };

// //Carret del servidor
// let arrayCart = getCookie("arrayCart");

//Quan vas cap enrere recarga la pàgina
window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
        location.reload();
    }
});
