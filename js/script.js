// ========== Navbar Function
window.addEventListener("scroll", function () {
    const header = document.querySelector('header');
    header.classList.toggle("sticky", window.scrollY > 0);
})

// ========== Toggle Menu
function toggleMenu() {
    const menuBar = document.querySelector(".menuToggle");
    const nav = document.querySelector(".nav");
    menuBar.classList.toggle('active');
    nav.classList.toggle('active');
}

// ========== Login Page
const formOpenBtn = document.querySelector("#form-open"),
    house = document.querySelector(".house"),
    formContainer = document.querySelector(".form_container"),
    formCloseBtn = document.querySelector(".form_close"),
    signupBtn = document.querySelector("#signup"),
    loginBtn = document.querySelector("#login"),
    pwShowHide = document.querySelectorAll(".pw_hide");

formOpenBtn.addEventListener("click", () => house.classList.add("show"));
formCloseBtn.addEventListener("click", () => house.classList.remove("show"));

pwShowHide.forEach((icon) => {
    icon.addEventListener("click", () => {
        let getPwInput = icon.parentElement.querySelector("input");
        if (getPwInput.type === "password") {
            getPwInput.type = "text";
            icon.classList.replace("uil-eye-slash", "uil-eye");
        } else {
            getPwInput.type = "password";
            icon.classList.replace("uil-eye", "uil-eye-slash");
        }
    });
});

signupBtn.addEventListener("click", (e) => {
    e.preventDefault();
    formContainer.classList.add("active");
});
loginBtn.addEventListener("click", (e) => {
    e.preventDefault();
    formContainer.classList.remove("active");
});