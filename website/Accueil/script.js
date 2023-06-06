const header = document.querySelector('.header');
const headerheight = header.offsetHeight;
const iconClose = document.querySelector('.icon-close');
const btn = document.querySelector('.chatbot-btn');
const chatbot = document.querySelector('.chatbot');

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

iconClose.addEventListener('click', ()=>{
    chatbot.classList.add('chatbot-hide');
    btn.classList.remove('chatbot-hide');
    btn.classList.add('chatbot-display');
    document.getElementById("chatbot-form").reset();
});

function chatbotDisplay(){
     chatbot.classList.remove('chatbot-hide');
     chatbot.classList.add('chatbot-display');
     btn.classList.remove('chatbot-display');
     btn.classList.add('chatbot-hide');
}
