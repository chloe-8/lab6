function closeModal() {
    document.getElementById("successModal").style.display = "none";
    window.history.replaceState({}, document.title, "add_user.php");
}

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get("success") === "1") {
        document.getElementById("successModal").style.display = "flex";
    }
}