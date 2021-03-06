//Navigation
function showMenu() {
    document.querySelector(".overlay").style.display = "flex";
}
function closeMenu() {
    document.querySelector(".overlay").style.display = "none";
}
//Initialisierungsfunktion für Ausführung der einzelnen Dingen
let ort = window.location.href;
console.log(ort);
//Balken animieren und einfärben unterseite Übersicht
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
//Statusboxen einfärben unterseite lastreset und Profil
if (ort == "https://cash48.ch/lastreset.php" || ort == "https://cash48.ch/profile.php") {
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
//Meldungen verschwinden lassen unterseite 
function verschwinden() {
    let erfolgsmeldung = document.querySelector("#erfolg");
    erfolgsmeldung.classList.add('weg');
}
//Zurück-Button auf der Startseite
function goBack() {
    history.go(-1);
}
//Test Checkbox zum alles anwählen
function toggle(source) {
    let checkboxes = document.getElementsByName('zuweisung[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}