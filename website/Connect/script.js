const principal = document.querySelector('.principal');
const Connexion = document.querySelector('.connexion');
const Inscription = document.querySelector('.inscription');
const btnPopup = document.querySelector('.popup');
const iconClose = document.querySelector('.icon-close');


Inscription.addEventListener('click', ()=>{
    principal.classList.add('active');
    document.getElementById("formulaire1").reset();
    document.getElementById("mdp_insc").setAttribute("type", "password");
});

Connexion.addEventListener('click', ()=>{
    principal.classList.remove('active');
    document.getElementById("formulaire2").reset();
    document.getElementById("mdp_connex").setAttribute("type", "password");
});

btnPopup.addEventListener('click', ()=>{
    principal.classList.add('active-popup');
});

iconClose.addEventListener('click', ()=>{
    principal.classList.remove('active-popup');
    principal.classList.remove('active');
    document.getElementById("formulaire1").reset();
    document.getElementById("formulaire2").reset();
});

// recuperer tout les input de type password et l'affiche
function AffichePass(id){
  var elem = document.getElementById(id);
  var type = elem.getAttribute("type");

  if (type === "password") {
    elem.setAttribute("type", "text");
  } else {
    elem.setAttribute("type", "password");
  } 
}
