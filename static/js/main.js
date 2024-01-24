const navMenu = document.querySelector('.js-nav__menu'),
      navToggle = document.querySelector('.js-nav__toggle'),
      navClose = document.querySelector('.js-nav__close'),
      navLink = document.querySelectorAll('.js-nav__item');

if (navToggle) {
  navToggle.addEventListener('click', () => {
    navMenu.classList.add('show-menu');
  });
}

if (navClose) {
  navClose.addEventListener('click', () => {
    navMenu.classList.remove('show-menu');
  });
}

const linkAction = () => {
  navMenu.classList.remove('show-menu');
}
navLink.forEach(l => l.addEventListener('click', linkAction));


const searchBar = document.querySelector('#swap');
if (searchBar) {
  searchBar.addEventListener('click', (e) => {
    e.preventDefault();
    let fromValue = document.querySelector('#from').value;
    let toValue = document.querySelector('#to').value;
    if (fromValue && toValue) {
      document.querySelector('#from').value = toValue;
      document.querySelector('#to').value = fromValue;
    }
  });
}

const searchSeats = document.querySelector('#seats');
if (searchSeats) {
  searchSeats.addEventListener('input', (e) => {
    e.preventDefault();
    let value = parseInt(searchSeats.value, 10);
    if (isNaN(value) || value < 1 || value > 20) {
      searchSeats.value = Math.min(20, Math.max(1, value));
    }
  });
}

const newsCard = document.querySelectorAll('.js-news');
newsCard.forEach(card => {
  let newsExpandButton = card.querySelector('.js-news__expand');
  let newsDescription = card.querySelector('.js-news__content');
  newsExpandButton.addEventListener('click', () => {
    newsDescription.classList.toggle('show');
    newsExpandButton.classList.toggle('rotate');
    newsExpandButton.classList.contains('rotate') ? 
    newsExpandButton.setAttribute('aria-label', 'Riduci la notizia') : 
    newsExpandButton.setAttribute('aria-label', 'Espandi la notizia');
  });
});

const overviewButton = document.querySelector('#overview');
const overviewPage = document.querySelector('#page--overview');
const subscriptionButton = document.querySelector('#subscription');
const subscriptionPage = document.querySelector('#page--subscription');
const userButton = document.querySelector('#user');
const userPage = document.querySelector('#page--user');

function showPageUser(button, page) {
  overviewPage.classList.add('container--hide');
  subscriptionPage.classList.add('container--hide');
  userPage.classList.add('container--hide');

  overviewButton.classList.remove('nav__button--current');
  subscriptionButton.classList.remove('nav__button--current');
  userButton.classList.remove('nav__button--current');

  page.classList.remove('container--hide');
  button.classList.add('nav__button--current');
  localStorage.setItem('userPage', button.id);
}

if (overviewButton && subscriptionButton && userButton) {
  overviewButton.addEventListener('click', () => showPageUser(overviewButton, overviewPage));
  subscriptionButton.addEventListener('click', () => showPageUser(subscriptionButton, subscriptionPage));
  userButton.addEventListener('click', () => showPageUser(userButton, userPage));
}

const addNewsButton = document.querySelector('#addNews');
const addNewsPage = document.querySelector('#page--addNews');
const addOfferButton = document.querySelector('#addOffer');
const addOfferPage = document.querySelector('#page--addOffer');

function showPageAdmin(button, page) {
  addNewsPage.classList.add('container--hide');
  addOfferPage.classList.add('container--hide');

  addNewsButton.classList.remove('nav__button--current');
  addOfferButton.classList.remove('nav__button--current');

  page.classList.remove('container--hide');
  button.classList.add('nav__button--current');
  localStorage.setItem('adminPage', button.id);
}

if (addNewsButton && addOfferButton) {
  addNewsButton.addEventListener('click', () => showPageAdmin(addNewsButton, addNewsPage));
  addOfferButton.addEventListener('click', () => showPageAdmin(addOfferButton, addOfferPage));
}

window.onload = () => {
  if (window.location.href.includes('adminpage') && localStorage.getItem('adminPage')) {
    let page = localStorage.getItem('adminPage');
    document.querySelector(`#${page}`).click();
  }
  else if (window.location.href.includes('userpage') && localStorage.getItem('userPage')) {
    let page = localStorage.getItem('userPage');
    document.querySelector(`#${page}`).click();
  }
}


const tickets = document.querySelectorAll('.js-ticket');
if (tickets) {
  tickets.forEach(ticket => {
    const ticketExpandButton = ticket.querySelector('.js-news__expand');
    const ticketBody = ticket.querySelector('.js-ticket__body');
    ticketExpandButton.addEventListener('click', () => {
      ticketBody.classList.toggle('ticket__body--reduced');
      ticketExpandButton.classList.toggle('rotate');
      ticketExpandButton.classList.contains('rotate') ? 
      ticketExpandButton.setAttribute('aria-label', 'Riduci il biglietto') : 
      ticketExpandButton.setAttribute('aria-label', 'Espandi il biglietto');
    });

    const firstClass = ticket.querySelector('.js-first__class');
    const secondClass = ticket.querySelector('.js-second__class');
    const priceButton = ticket.querySelector('.js-submit');
    firstClass.addEventListener('click', () => {
      firstClass.classList.add('ticket__class--selected');
      secondClass.classList.remove('ticket__class--selected');
      priceButton.innerHTML = `€${priceButton.dataset.firstclass}`
    });
    secondClass.addEventListener('click', () => {
      secondClass.classList.add('ticket__class--selected');
      firstClass.classList.remove('ticket__class--selected');
      priceButton.innerHTML = `€${priceButton.dataset.secondclass}`
    });
  });
}