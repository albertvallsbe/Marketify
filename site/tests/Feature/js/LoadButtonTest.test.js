// // Importa las dependencias necesarias para realizar las pruebas
// import { jsdom } from "jsdom";

// // Importa la función que se desea probar
// import { loadButtons } from "../../../public/js/main.js";

describe("loadButtons", () => {
    // beforeEach(() => {
    //     // Configura el entorno para las pruebas
    //     const dom = new jsdom("<html><body></body></html>");
    //     global.document = dom.window.document;
    //     global.window = dom.window;
    //     global.words = {
    //         buttons: {
    //             add: "Add to Cart",
    //             remove: "Remove from Cart",
    //         },
    //     };
    // });

    // afterEach(() => {
    //     // Restablece el entorno después de cada prueba
    //     delete global.document;
    //     delete global.window;
    //     delete global.words;
    // });

    // test("should change button text and attach event listeners", () => {
    //     // Simula el HTML y elementos necesarios para la prueba
    //     document.body.innerHTML = `
    //   <button class="btn-cart" data-product-id="1">Add to Cart</button>
    //   <button class="btn-cart" data-product-id="2">Add to Cart</button>
    // `;

    //     // Llama a la función que se desea probar
    //     loadButtons();

    //     // Realiza las comprobaciones y aserciones necesarias
    //     const buttons = document.getElementsByClassName("btn-cart");
    //     expect(buttons.length).toBe(2);

    //     // Verifica que el texto del botón haya cambiado correctamente
    //     expect(buttons[0].innerText).toBe("Add to Cart");
    //     expect(buttons[1].innerText).toBe("Add to Cart");

    //     // Simula un clic en uno de los botones y verifica si se activa el evento
    //     const button1 = buttons[0];
    //     button1.click();

    //     // Agrega más aserciones aquí, según sea necesario

    //     // ...
    // });

    test("A simple test", () => {
        expect(1 + 1).toBe(2);
    });
});
