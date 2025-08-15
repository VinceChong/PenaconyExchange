const loginTab = document.getElementById("loginTab");
const signUpTab = document.getElementById("signUpTab");
const loginForm = document.getElementById("loginForm");
const signUpForm = document.getElementById("signUpForm");

loginTab.addEventListener("click", () => {
    loginTab.classList.add("active");
    signUpTab.classList.remove("active");
    loginForm.classList.add("active");
    signUpForm.classList.remove("active");
})

signUpTab.addEventListener("click", () => {
    loginTab.classList.remove("active");
    signUpTab.classList.add("active");
    loginForm.classList.remove("active");
    signUpForm.classList.add("active");
})

loginForm.addEventListener("submit", function(e) {

    const formData = new FormData(this);

    fetch("/PenaconyExchange/db/login.php", {
        method: "POST",
        body: formData,
    })
    .then( (response) => response.json())
    .then( (data) => {
        const messageBox = document.getElementById("loginMessage");
        
        if (data.success) {
            messageBox.innerHTML = `<div class="alert success">${data.message}</div>`;

            setTimeout(() => {
                window.location.href = "../pages/home.php";
            }, 1000);
        } else {
            messageBox.innerHTML = `<div class="alert error">${data.message}</div>`;
        }
    })
    .catch( (error) => {
        console.error("Error: ", error);
    })
})