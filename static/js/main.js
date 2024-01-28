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


const searchForm = document.querySelector('.js-container__form--search');
const searchSwap = document.querySelector('#swap');
const searchFrom = document.querySelector('#from');
const searchTo = document.querySelector('#to');
const searchDate = document.querySelector('#date');
if (searchSwap && searchFrom && searchTo) {
  searchSwap.addEventListener('click', (e) => {
    e.preventDefault();
    let fromValue = document.querySelector('#from').value;
    let toValue = document.querySelector('#to').value;
    if (fromValue && toValue) {
      document.querySelector('#from').value = toValue;
      document.querySelector('#to').value = fromValue;
    }
  });

  searchFrom.addEventListener('focus', (e) => {
    e.preventDefault();
    if(searchForm.querySelector('#departure_station_error')) searchForm.querySelector('#departure_station_error').remove();
    else if(searchForm.querySelector('#departure_station_empty')) searchForm.querySelector('#departure_station_empty').remove();
  });
  searchFrom.addEventListener('blur', (e) => {
    e.preventDefault();
    if (searchFrom.value == undefined || searchFrom.value == '') 
      searchForm.insertAdjacentHTML('afterbegin', '<p id="departure_station_error" class="form__error">Inserisci la stazione di partenza</p>');
  });
  searchFrom.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      searchTo.focus();
    }
  });

  searchTo.addEventListener('focus', (e) => {
    e.preventDefault();
    if(searchForm.querySelector('#arrival_station_error')) searchForm.querySelector('#arrival_station_error').remove();
    else if(searchForm.querySelector('#arrival_station_empty')) searchForm.querySelector('#arrival_station_empty').remove();
  });
  searchTo.addEventListener('blur', (e) => {
    e.preventDefault();
    if (searchTo.value == undefined || searchTo.value == '') 
      searchForm.insertAdjacentHTML('afterbegin', '<p id="arrival_station_error" class="form__error">Inserisci la stazione di arrivo</p>');
  });
  searchTo.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      searchSwap.focus();
    }
  });

  searchDate.addEventListener('blur', () => {
    if (new Date(searchDate.value) > new Date() && searchForm.querySelector('#datetime_error')) 
      searchForm.querySelector('#datetime_error').remove();
    else if (new Date(searchDate.value) <= new Date() && !searchForm.querySelector('#datetime_error'))
      searchForm.insertAdjacentHTML('afterbegin', '<p id="datetime_error" class="form__error">La data e l&#39;ora devono essere maggiori o uguali a quelli attuali</p>');
  });
}

const searchSeats = document.querySelector('#seats');
if (searchSeats) {
  searchSeats.addEventListener('input', (e) => {
    e.preventDefault();
    let value = parseInt(searchSeats.value, 10);
    if (isNaN(value) || value < 1 || value > 35) {
      searchSeats.value = Math.min(35, Math.max(1, value));
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
    const submitButton = ticket.querySelector('.js-submit');
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
    submitButton.addEventListener('click', () => {
      const form = document.createElement('form');
      form.method = 'post';
      form.action = './buy.php';
      const createHiddenInput = (name, value) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        return input;
      };
      form.appendChild(createHiddenInput('route', submitButton.dataset.route));
      form.appendChild(createHiddenInput('schedule', submitButton.dataset.schedule));
      form.appendChild(createHiddenInput('date', submitButton.dataset.date));
      form.appendChild(createHiddenInput('departure', submitButton.dataset.departure));
      form.appendChild(createHiddenInput('arrival', submitButton.dataset.arrival));
      ticket.appendChild(form);
      form.submit();
    });
  });
}