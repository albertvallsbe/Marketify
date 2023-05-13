const toggleSwitch = document.querySelector("#mode-toggle");
const img = toggleSwitch.querySelector("img");

const imageUrl = window.location.origin + "/images/icons/";
const sunImage = imageUrl + "moon-solid.svg";
const moonImage = imageUrl + "sun-solid.svg";

const currentMode = localStorage.getItem("mode");
if (currentMode === "dark" && window.location.href.indexOf('/shop/') === -1) {
    setDarkMode();
}

toggleSwitch.addEventListener("click", function () {
    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("mode", "light");
        setLightMode();
    } else {
        if (window.location.href.indexOf('/shop/') === -1) {
            localStorage.setItem("mode", "dark");
            setDarkMode();            
        }else{
            alert("You can not set dark mode in a shop!")
        }
    }
});

function setDarkMode() {
    document.body.classList.add("dark-mode");
    img.src = moonImage;
    img.alt = "Change to diurn mode";
}

function setLightMode() {
    document.body.classList.remove("dark-mode");
    img.src = sunImage;
    img.alt = "Change to night mode";
}
