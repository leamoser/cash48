function showMenu() {
    document.querySelector(".overlay").style.display = "flex";
}
function closeMenu() {
    document.querySelector(".overlay").style.display = "none";
}

let ort = window.location.href;

//Balken
if (ort == 'https://cash48.ch/overview.php') {
    let balken = document.querySelectorAll(".balken");
    let allewerte = [];
    for (i = 0; i < balken.length; i++) {
        let wert = balken[i].attributes[0].value;
        balken[i].style.width = 0 + '%';
        balken[i].style.transition = 0.9 + 's';
        allewerte.push(Math.abs(wert));
        if (wert > 0) {
            balken[i].classList.add('positiv');
        } else if (wert < 0) {
            balken[i].classList.add('negativ');
        } else {
            balken[i].classList.add('neutral');
        }
    }
    console.log(allewerte);
    let max = Math.max.apply(null, allewerte);
    let verrechnungswert = 100 / max;
    for (i = 0; i < balken.length; i++) {
        let wert = balken[i].attributes[0].value;
        balken[i].style.width = Math.abs(wert) * verrechnungswert + '%';
    }

}

if (ort == "https://cash48.ch/lastreset.php") {
    let status = document.querySelectorAll(".status");
    for (i = 0; i < status.length; i++) {
        let wert = status[i].attributes[0].value;
        if (wert == 1) {
            status[i].classList.add('paid');
        } else {
            status[i].classList.add('open');
        }
    }
}
