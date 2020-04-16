function showMenu() {
    document.querySelector(".overlay").style.display = "flex";
}
function closeMenu() {
    document.querySelector(".overlay").style.display = "none";
}

//Balken
let balken = document.querySelectorAll(".balken");
for (i = 0; i < balken.length; i++) {
    let wert = balken[i].attributes[0].value;
    console.log(wert);
    balken[i].style.width = Math.abs(wert) + 'px';
    if (wert > 0) {
        balken[i].classList.add('positiv');
    } else if (wert < 0) {
        balken[i].classList.add('negativ');
    } else {
        balken[i].classList.add('neutral');
    }
}
console.log(balken);