const sidenav = document.getElementById("sidenav-1");
const sidenavInstance = mdb.Sidenav.getInstance(sidenav);

let innerWidth = null;

const setMode = (e) => {
    // Check necessary for Android devices
    if (window.innerWidth === innerWidth) {
        return;
    }

    innerWidth = window.innerWidth;

    if (window.innerWidth < 492) { // Cambia el número según tus necesidades de diseño responsivo
        sidenavInstance.changeMode("over");
        sidenavInstance.hide();
    } else {
        sidenavInstance.changeMode("side");
        sidenavInstance.show();
    }
};

setMode(); // Llamada inicial para configurar el modo correcto al cargar la página

// Agrega un event listener para detectar cambios en el tamaño de la ventana
window.addEventListener("resize", setMode);
