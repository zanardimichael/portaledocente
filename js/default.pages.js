document.addEventListener("DOMContentLoaded", () => {

    if(createSuccess) {
        Toastify({
            text: "Creazione effettuata con successo",
            duration: 3000,
            style: {"background": "green"}
        }).showToast();
    }
})