document.addEventListener("DOMContentLoaded", function () {
    const barberButton = document.getElementById("barber-button");
    const icon = barberButton.querySelector("i");

    // Set initial theme based on localStorage
    if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark-mode");
        icon.classList.replace("fa-cut", "fa-sun");
    } else {
        icon.classList.replace("fa-sun", "fa-cut");
    }

    barberButton.addEventListener("click", function () {
        document.body.classList.toggle("dark-mode");
        if (document.body.classList.contains("dark-mode")) {
            localStorage.setItem("theme", "dark");
            icon.classList.remove("fa-cut");
            icon.classList.add("fa-sun");
        } else {
            localStorage.setItem("theme", "light");
            icon.classList.remove("fa-sun");
            icon.classList.add("fa-cut");
        }
    });
});
