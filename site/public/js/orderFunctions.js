//Comprova si hi ha una sessió iniciada
const updateOrdersServerStorage = (arrayOrderToServer, shop_id) => {
    try {
        fetch("/auth")
            .then((response) => response.json())
            .then((data) => {
                if (data.authenticated) {
                    // console.log(`uptadeOrdersSErver... ${arrayOrderToServer}`);
                    addToServerOrder(arrayOrderToServer, shop_id);
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
        // // let shop_id = "2";
        // // let arrayOrderToServer = ["1", "3", "4"];
        // // arrayOrderToServer = JSON.stringify(arrayOrderToServer);
        // fetch("/orders/", {
        //     method: "POST",
        //     headers: {
        //         "Content-Type": "application/json",
        //         "X-CSRF-TOKEN": document
        //             .querySelector('meta[name="csrf-token"]')
        //             .getAttribute("content"),
        //     },
        //     // body: JSON.stringify({ arrayOrderToServer }),
        // }).then(() => {});
    } catch (error) {
        console.error(error);
    }
}
export { addToServerOrder };
export { updateOrdersServerStorage };

// //Carret del servidor
// let arrayCart = getCookie("arrayCart");

//Quan vas cap enrere recarga la pàgina
window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
        location.reload();
    }
});
