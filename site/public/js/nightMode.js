const toggleSwitch = document.querySelector("#mode-toggle");
const img = toggleSwitch.querySelector("img");

const imageUrl = window.location.origin + "/images/";
const sunImage = imageUrl + "moon-solid.svg";
const moonImage = imageUrl + "sun-solid.svg";

const currentMode = localStorage.getItem("mode");
if (currentMode === "dark") {
    setDarkMode();
}

toggleSwitch.addEventListener("click", function () {
    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("mode", "light");
        setLightMode();
    } else {
        localStorage.setItem("mode", "dark");
        setDarkMode();
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
