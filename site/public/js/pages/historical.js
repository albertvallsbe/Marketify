

function showPrice(){
    const prices = document.querySelectorAll(".price-product");
    const price_title = document.querySelector(".total-price");
    let total = 0;
    for (let i = 0; i < prices.length; i++) {
        const priceText = prices[i].textContent;
        total += parseFloat(priceText);     
    }
    price_title.textContent = `Total: ${total}.00 €`;
}
showPrice();