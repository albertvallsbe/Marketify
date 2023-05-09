import "../app.js";
import words from "../dictionaryWords.js";

const headboard = document.querySelector(".headboard");
const textColor = isDarkColor(headboard.style.backgroundColor)
    ? "#fff"
    : "#222";
headboard.style.color = textColor;

function isDarkColor(color) {
    const [r, g, b] = color.match(/\d+/g).map(Number);
    const brightness = Math.round((r * 299 + g * 587 + b * 114) / 1000);
    return brightness < 128;
}

const buttonLabel = document.getElementById("button-prueba");
// console.log(buttonLabel);
// console.log(words);
buttonLabel.innerText = words.buttons.add;
