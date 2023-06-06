const span = document.querySelector('.display');
const img = document.querySelector('img');
const pp = document.getElementById("pp");
const uploadForm = document.getElementById("uploadForm");
const header = document.querySelector('.header');
const headerheight = header.offsetHeight;

 img.addEventListener('mouseover', () => {
   span.style.display = 'block';
 });

img.addEventListener('mouseout', () => {
    span.style.display = 'none';
});


img.addEventListener("click", () => {
    pp.click();
});

span.addEventListener('click', () => {
    pp.click();
});
  
pp.addEventListener("change", () => {
    const file = pp.files[0];
    if (file) {
        const reader = new FileReader();
        reader.addEventListener("load", () => {
            img.src = reader.result;
        });
        reader.readAsDataURL(file);
    }
});

window.addEventListener('scroll', ()=>{
    const scroll = window.scrollY;
    if(scroll > 30){
        header.classList.add('brouillard');
    }else{
        header.classList.remove('brouillard');
    }
});