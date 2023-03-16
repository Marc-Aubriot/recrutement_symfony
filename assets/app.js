/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

let newConsultantBtn = document.getElementById('newConsultantBtnID');
let newConsultantFormDiv = document.getElementById('newConsultantFormDivID');
let showNewConsultantFormDiv = false;
newConsultantBtn.addEventListener('click', ()=> {
    showNewConsultantFormDiv = !showNewConsultantFormDiv;
    newConsultantFormDiv.classList = showNewConsultantFormDiv ? "showDiv" : "hiddenDiv";
});

