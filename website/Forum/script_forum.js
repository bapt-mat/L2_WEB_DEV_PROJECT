const header = document.querySelector('.header');
const headerheight = header.offsetHeight;

window.addEventListener('scroll', ()=>{
    const scroll = window.scrollY;
    console.log(headerheight);
    console.log(scroll);
    if(scroll > headerheight){
        header.classList.add('brouillard');
    }else{
        header.classList.remove('brouillard');
    }
});