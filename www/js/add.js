let serie = document.getElementById("serie");
serie.addEventListener("change", changer);
let divserie = document.getElementById("divserie");
let divtome = document.getElementById("divtome");

function changer() {
    divnom = document.getElementById("new");
    divnbtome = document.getElementById("nbtome");
    divnbhs = document.getElementById("nbhs");
    if (serie.value == "autre") {
        html = `<div class="col-md-12" id="new">
                <label class="form-label fw-semibold">Nom Serie</label>
                <input type="text" class="form-control" name="nomserie" required/>
            </div>`;
        nbtome = `<div class="col-md-4" id="nbtome">
                    <label class="form-label fw-semibold">Nbr.Tome</label>
                    <input type="number" class="form-control" name="nbtome" required>
                </div>`;
        nbhs = `<div class="col-md-4" id="nbhs">
                    <label class="form-label fw-semibold">Nbr.Hors Serie</label>
                    <input type="number" class="form-control" name="nbhs" required>
                </div>`;
        divserie.insertAdjacentHTML("afterend", html);
        divtome.insertAdjacentHTML("afterend", nbhs);
        divtome.insertAdjacentHTML("afterend", nbtome);
    } else {
        divnom?.remove();
        divnbtome?.remove();
        divnbhs?.remove();
    }
}
changer();
