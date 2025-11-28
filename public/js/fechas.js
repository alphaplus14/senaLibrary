let hoy = new Date();
let yyyy = hoy.getFullYear();
let mm = String(hoy.getMonth() + 1).padStart(2, "0");
let dd = String(hoy.getDate()).padStart(2, "0");
let fechaHoy = `${yyyy}-${mm}-${dd}`;

let futuro = new Date();
futuro.setDate(futuro.getDate() + 45);

let yyyyF = futuro.getFullYear();
let mmF = String(futuro.getMonth() + 1).padStart(2, "0");
let ddF = String(futuro.getDate()).padStart(2, "0");
let fechaMax = `${yyyyF}-${mmF}-${ddF}`;

let inputFecha = document.getElementById("fechaRecogida");
inputFecha.setAttribute("min", fechaHoy);
inputFecha.setAttribute("max", fechaMax);
