
document.addEventListener("DOMContentLoaded", function () {
    const menuBtn = document.getElementById("seoMenuToggle");
    const nav = document.getElementById("seoNav");

    if (!menuBtn || !nav) {
        console.error("Menu button or navigation not found.");
        return;
    }

    function closeMenu() {
        nav.classList.remove("show");
        menuBtn.setAttribute("aria-expanded", "false");
        menuBtn.setAttribute("aria-label", "Ouvrir le menu");
        menuBtn.innerHTML = '<i class="fa-solid fa-bars"></i>';
    }

    menuBtn.addEventListener("click", function () {
        const isOpen = nav.classList.toggle("show");

        menuBtn.setAttribute("aria-expanded", String(isOpen));
        menuBtn.setAttribute(
            "aria-label",
            isOpen ? "Fermer le menu" : "Ouvrir le menu"
        );

        menuBtn.innerHTML = isOpen
            ? '<i class="fa-solid fa-xmark"></i>'
            : '<i class="fa-solid fa-bars"></i>';
    });

    nav.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", closeMenu);
    });

    window.addEventListener("resize", function () {
        if (window.innerWidth > 900) {
            closeMenu();
        }
    });
});
