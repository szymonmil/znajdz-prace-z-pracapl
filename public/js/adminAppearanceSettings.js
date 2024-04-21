// Czyszczenie pól
window.addEventListener("DOMContentLoaded", (event) => {
    document.getElementById("resetTitleFontSize").addEventListener("click", () => titleFontSizeInput.value = "");
    document.getElementById("resetTitleColor").addEventListener("click", () => titleColorInput.value = "");
    document.getElementById("resetAdditionalInfoFontSize").addEventListener("click", () => additionalInfoFontSizeInput.value = "");
    document.getElementById("resetAdditionalInfoColor").addEventListener("click", () => additionalInfoColorInput.value = "");
});