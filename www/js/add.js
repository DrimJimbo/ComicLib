let btnadd = document.getElementById("btnadd");
btnadd.addEventListener("click", add);
let div = document.getElementById("ajout");
function add() {
    comic = `<div class="border-bottom border-top border-secondary">           
                        <div class='col-md-12'>
                            <label class='form-label fw-semibold'>Titre français</label>
                            <input type='text' class='form-control' name='titre_com[]' required>
                        </div>
                        <div class='col-md-12'>
                            <label class='form-label fw-semibold'>Titre anglais</label>
                            <input type='text' class='form-control' name='titre_en_com[]'>
                        </div>
                        <div class='col-md-8'>
                            <label class='form-label fw-semibold'>Série</label>
                            <input type='text' class='form-control' name='serie_com[]'>
                        </div>
                        <div class='col-md-4'>
                            <label class='form-label fw-semibold'>Tome</label>
                            <input type='number' class='form-control' name='tome_com[]'>
                        </div>
                        <div class='col-12'>
                            <label class='form-label fw-semibold'>Date de sortie</label>
                            <input type='date' class='form-control' name='date_com[]'>
                        </div>
            </div>`;
    div.insertAdjacentHTML("beforeend", comic);
}
