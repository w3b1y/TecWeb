////////////////////////////////////  NAVBAR  ////////////////////////////////////
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



////////////////////////////////////  HOME  ////////////////////////////////////
const url = new URL(window.location.href);
const searchForm = document.querySelector('.js-container__form--search');
const searchSwap = document.querySelector('#swap');
const searchFrom = document.querySelector('#from');
const searchTo = document.querySelector('#to');
const searchDiscount = document.querySelector('#discount');
const searchDate = document.querySelector('#date');
const searchSeats = document.querySelector('#seats');
if (searchForm) {
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


  if (url.searchParams.get("discount_code")) searchDiscount.value = url.searchParams.get("discount_code");
  if (url.searchParams.get("min_people")) searchSeats.value = url.searchParams.get("min_people");
  searchDiscount.addEventListener('focus', (e) => {
    e.preventDefault();
    if(searchForm.querySelector('#discount_error')) searchForm.querySelector('#discount_error').remove();
  });

  searchSeats.addEventListener('input', (e) => {
    e.preventDefault();
    let value = parseInt(searchSeats.value, 10);
    if (isNaN(value) || value < 1 || value > 35) {
      searchSeats.value = Math.min(35, Math.max(1, value));
    }
  });
}



////////////////////////////////////  NEWS  ////////////////////////////////////
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



////////////////////////////////////  TICKETS  ////////////////////////////////////
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
      priceButton.dataset.class = 1;
    });
    secondClass.addEventListener('click', () => {
      secondClass.classList.add('ticket__class--selected');
      firstClass.classList.remove('ticket__class--selected');
      priceButton.innerHTML = `€${priceButton.dataset.secondclass}`
      priceButton.dataset.class = 2;
    });
    submitButton.addEventListener('click', () => {
      const params = new URLSearchParams();
      params.append('schedule', submitButton.dataset.schedule);
      params.append('class', submitButton.dataset.class);
      params.append('price', submitButton.dataset.class == 1 ? priceButton.dataset.firstclass : priceButton.dataset.secondclass);
      params.append('departure_time', ticket.querySelector('.js-departure_time').innerHTML);
      params.append('arrival_time', ticket.querySelector('.js-arrival_time').innerHTML);

      fetch(window.location.href, {
          method: 'POST',
          body: params
      })
      .then(response => {
        if (response.ok) {
          window.location.href = './buy.php';
        } else {
            throw new Error('Network response was not ok.');
        }
      });
    });
  });
}



////////////////////////////////////  USERPAGE & ADMINPAGE  ////////////////////////////////////
const overviewButton = document.querySelector('#overview');
const overviewPage = document.querySelector('#page--overview');
const subscriptionButton = document.querySelector('#subscription');
const subscriptionPage = document.querySelector('#page--subscription');
const userButton = document.querySelector('#user');
const userPage = document.querySelector('#page--user');
const logOutButton = document.querySelector('#logout');

if (logOutButton) {
  logOutButton.addEventListener('click', () => {
    localStorage.removeItem('userPage');
    localStorage.removeItem('adminPage');
    const currentUrl = window.location.href;
    const baseUrl = currentUrl.split('/').slice(0, -1).join('/');

    window.location.href = baseUrl + '/logout.php';
  });
}

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


////////////////////////////////////  LOGIN  ////////////////////////////////////
const loginForm = document.querySelector('.js-login__form');
if (loginForm) {
  const email = loginForm.querySelector('#email');
  const password = loginForm.querySelector('#new_password');
  const emailRegex = /^(?!.*\.\.)[a-zA-Z0-9]+([._]*[a-zA-Z0-9])*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  email.addEventListener('blur', (e) => {
    e.preventDefault();
    if (!emailRegex.test(email.value) && !loginForm.querySelector('#email_error')) 
      loginForm.insertAdjacentHTML('afterbegin', '<p id="email_error" class="form__error">Inserisci un indirizzo email valido</p>');
    else if (emailRegex.test(email.value) && loginForm.querySelector('#email_error')) loginForm.querySelector('#email_error').remove();
  });
  email.addEventListener('focus', (e) => {
    e.preventDefault();
    if (password.value && loginForm.querySelector('#login_error')) loginForm.querySelector('#login_error').remove();
  });
  password.addEventListener('blur', (e) => {
    e.preventDefault();
    if (password.value.length < 8 && !loginForm.querySelector('#password_error')) 
      loginForm.insertAdjacentHTML('afterbegin', '<p id="password_error" class="form__error">La password deve essere lunga almeno 8 caratteri</p>');
    else if (password.value.length >= 8 && loginForm.querySelector('#password_error')) loginForm.querySelector('#password_error').remove();
  });
  password.addEventListener('focus', (e) => {
    e.preventDefault();
    if (email.value && loginForm.querySelector('#login_error')) loginForm.querySelector('#login_error').remove();
  });
}


////////////////////////////////////  REGISTER  ////////////////////////////////////
const registerForm = document.querySelector('.js-register__form');
if (registerForm) {
  const pInfoFieldset = registerForm.querySelector('.js-register__form--pinfo');
  const pwdFieldset = registerForm.querySelector('.js-register__form--pwd');
  const name = registerForm.querySelector('#name');
  const surname = registerForm.querySelector('#surname');
  const email = registerForm.querySelector('#email');
  const birthday = registerForm.querySelector('#birthday');
  const password = registerForm.querySelector('#new_password');
  const rpassword = registerForm.querySelector('#rnew_password');
  const emailRegex = /^(?!.*\.\.)[a-zA-Z0-9]+([._]*[a-zA-Z0-9])*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  name.addEventListener('blur', () => {
    if (!name.value && !pInfoFieldset.querySelector('#name_error')) 
    pInfoFieldset.insertAdjacentHTML('afterbegin', '<p id="name_error" class="form__error">Inserisci il tuo nome</p>');
    else if (name.value && pInfoFieldset.querySelector('#name_error')) pInfoFieldset.querySelector('#name_error').remove();
  });
  surname.addEventListener('blur', () => {
    if (!surname.value && !pInfoFieldset.querySelector('#surname_error'))
      pInfoFieldset.insertAdjacentHTML('afterbegin', '<p id="surname_error" class="form__error">Inserisci il tuo cognome</p>');
    else if (surname.value && pInfoFieldset.querySelector('#surname_error')) pInfoFieldset.querySelector('#surname_error').remove();
  });
  email.addEventListener('blur', () => {
    if (!emailRegex.test(email.value) && !pInfoFieldset.querySelector('#email_error')) 
    pInfoFieldset.insertAdjacentHTML('afterbegin', '<p id="email_error" class="form__error">Inserisci un indirizzo email valido</p>');
    else if (emailRegex.test(email.value) && pInfoFieldset.querySelector('#email_error')) pInfoFieldset.querySelector('#email_error').remove();
  });
  birthday.addEventListener('blur', () => {
    if (new Date(birthday.value) >= new Date() && !pInfoFieldset.querySelector('#birthday_error')) 
      pInfoFieldset.insertAdjacentHTML('afterbegin', '<p id="birthday_error" class="form__error">La data di nascita deve essere minore o uguale a quella attuale</p>');
    else if (new Date(birthday.value) < new Date() && pInfoFieldset.querySelector('#birthday_error')) pInfoFieldset.querySelector('#birthday_error').remove();
  });
  password.addEventListener('blur', () => {
    if (password.value.length < 8 && !pwdFieldset.querySelector('#password_error')) 
      pwdFieldset.insertAdjacentHTML('afterbegin', '<p id="password_error" class="form__error">La password deve essere lunga almeno 8 caratteri</p>');
    else if (password.value.length >= 8 && pwdFieldset.querySelector('#password_error')) pwdFieldset.querySelector('#password_error').remove();

    if (rpassword.value != password.value && rpassword.value && !pwdFieldset.querySelector('#different_password_error')) 
      pwdFieldset.insertAdjacentHTML('afterbegin', '<p id="different_password_error" class="form__error">Le password inserite sono differenti</p>');
    else if (rpassword.value == password.value && pwdFieldset.querySelector('#different_password_error')) pwdFieldset.querySelector('#different_password_error').remove();
  });
  rpassword.addEventListener('blur', () => {
    if (rpassword.value.length < 8 && !pwdFieldset.querySelector('#rpassword_error')) 
      pwdFieldset.insertAdjacentHTML('afterbegin', '<p id="rpassword_error" class="form__error">La password deve essere lunga almeno 8 caratteri</p>');
    else if (rpassword.value.length >= 8 && pwdFieldset.querySelector('#rpassword_error')) pwdFieldset.querySelector('#rpassword_error').remove();

    if (rpassword.value != password.value && !pwdFieldset.querySelector('#different_password_error')) 
      pwdFieldset.insertAdjacentHTML('afterbegin', '<p id="different_password_error" class="form__error">Le password inserite sono differenti</p>');
    else if (rpassword.value == password.value && pwdFieldset.querySelector('#different_password_error')) pwdFieldset.querySelector('#different_password_error').remove();
  });
}