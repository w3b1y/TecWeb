////////////////////////////////////  NAVBAR  ////////////////////////////////////
const navMenu = document.querySelector('.js-nav__menu'),
      navToggle = document.querySelector('.js-nav__toggle'),
      navClose = document.querySelector('.js-nav__close'),
      navLink = document.querySelectorAll('.js-nav__item');

if (navToggle) {
  navToggle.addEventListener('click', () => {
    navMenu.classList.remove('hide-menu');
    navMenu.classList.add('show-menu');
  });
}

if (navClose) {
  navClose.addEventListener('click', () => {
    navMenu.classList.add('hide-menu');
    navMenu.classList.remove('show-menu');
  });
}

const linkAction = () => {
  navMenu.classList.remove('show-menu');
}
navLink.forEach(l => l.addEventListener('click', linkAction));



////////////////////////////////////  HOME  ////////////////////////////////////
const url = new URL(window.location.href);
const successMessage = document.querySelector('.js-success-message');
const searchForm = document.querySelector('.js-container__form--search');
const searchSwap = document.querySelector('#swap');
const searchFrom = document.querySelector('#from');
const searchTo = document.querySelector('#to');
const searchDiscount = document.querySelector('#discount');
const searchDate = document.querySelector('#date');
const searchSeats = document.querySelector('#seats');
if (successMessage) {
  setTimeout(function() {
    if (successMessage && successMessage.parentNode) {
      successMessage.parentNode.removeChild(successMessage);
    }
  }, 5000);
}
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

  searchFrom.addEventListener('blur', () => {
    if(searchForm.querySelector('#departure_station_error')) searchForm.querySelector('#departure_station_error').remove();
    else if(searchForm.querySelector('#departure_station_empty')) searchForm.querySelector('#departure_station_empty').remove();
  });
  
  searchTo.addEventListener('blur', () => {
    if(searchForm.querySelector('#arrival_station_error')) searchForm.querySelector('#arrival_station_error').remove();
    else if(searchForm.querySelector('#arrival_station_empty')) searchForm.querySelector('#arrival_station_empty').remove();
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
const historyButton = document.querySelector('#history');
const historyPage = document.querySelector('#page--history');
const userButton = document.querySelector('#user');
const userPage = document.querySelector('#page--user');

function showPageUser(button, page) {
  overviewPage.classList.add('container--hide');
  historyPage.classList.add('container--hide');
  userPage.classList.add('container--hide');

  overviewButton.classList.remove('nav__button--current');
  historyButton.classList.remove('nav__button--current');
  userButton.classList.remove('nav__button--current');

  page.classList.remove('container--hide');
  button.classList.add('nav__button--current');
  localStorage.setItem('userPage', button.id);
}

if (overviewButton && historyButton && userButton) {
  overviewButton.addEventListener('click', () => showPageUser(overviewButton, overviewPage));
  historyButton.addEventListener('click', () => showPageUser(historyButton, historyPage));
  userButton.addEventListener('click', () => showPageUser(userButton, userPage));

  const userForm = document.querySelector('#form--user-data');
  const pInfoFieldset = userForm.querySelector('.js-register__form--pinfo');
  const pwdFieldset = userForm.querySelector('.js-register__form--pwd');
  const name = userForm.querySelector('#name');
  const surname = userForm.querySelector('#surname');
  const email = userForm.querySelector('#email');
  const birthday = userForm.querySelector('#birthday');
  const opassword = userForm.querySelector('#old_password');
  const password = userForm.querySelector('#new_password');
  const rpassword = userForm.querySelector('#rnew_password');
  const emailRegex = /^(?!.*\.\.)[a-zA-Z0-9]+([._]*[a-zA-Z0-9])*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  name.addEventListener('blur', () => {
    if (!name.value && !pInfoFieldset.querySelector('#name_error')) 
    pInfoFieldset.insertAdjacentHTML('afterbegin', '<p aria-role="alert" id="name_error" class="form__error">Inserisci il tuo nome</p>');
    else if (name.value && pInfoFieldset.querySelector('#name_error')) pInfoFieldset.querySelector('#name_error').remove();
  });
  surname.addEventListener('blur', () => {
    if (!surname.value && !pInfoFieldset.querySelector('#surname_error'))
      pInfoFieldset.insertAdjacentHTML('afterbegin', '<p aria-role="alert" id="surname_error" class="form__error">Inserisci il tuo cognome</p>');
    else if (surname.value && pInfoFieldset.querySelector('#surname_error')) pInfoFieldset.querySelector('#surname_error').remove();
  });
  email.addEventListener('blur', () => {
    if (!emailRegex.test(email.value) && !pInfoFieldset.querySelector('#email_error')) 
    pInfoFieldset.insertAdjacentHTML('afterbegin', '<p aria-role="alert" id="email_error" class="form__error">Inserisci un indirizzo email valido</p>');
    else if (emailRegex.test(email.value) && pInfoFieldset.querySelector('#email_error')) pInfoFieldset.querySelector('#email_error').remove();
  });
  birthday.addEventListener('blur', () => {
    if (new Date(birthday.value) >= new Date() && !pInfoFieldset.querySelector('#birthday_error')) 
      pInfoFieldset.insertAdjacentHTML('afterbegin', '<p aria-role="alert" id="birthday_error" class="form__error">La data di nascita deve essere minore o uguale a quella attuale</p>');
    else if (new Date(birthday.value) < new Date() && pInfoFieldset.querySelector('#birthday_error')) pInfoFieldset.querySelector('#birthday_error').remove();
  });
  opassword.addEventListener('blur', () => {
    if (opassword.value && opassword.value.length < 8 && !pwdFieldset.querySelector('#password_len_error')) 
      pwdFieldset.insertAdjacentHTML('afterbegin', '<p aria-role="alert" id="password_len_error" class="form__error">La password deve essere lunga almeno 8 caratteri</p>');
    else if (opassword.value.length >= 8 && pwdFieldset.querySelector('#password_len_error')) pwdFieldset.querySelector('#password_len_error').remove();
  });
  password.addEventListener('blur', () => {
    if (password.value && password.value.length < 8 && !pwdFieldset.querySelector('#password_len_error')) 
      pwdFieldset.insertAdjacentHTML('afterbegin', '<p aria-role="alert" id="password_len_error" class="form__error">La password deve essere lunga almeno 8 caratteri</p>');
    else if (password.value.length >= 8 && pwdFieldset.querySelector('#password_len_error')) pwdFieldset.querySelector('#password_len_error').remove();

    if (rpassword.value != password.value && rpassword.value && !pwdFieldset.querySelector('#different_password_error')) 
      pwdFieldset.insertAdjacentHTML('afterbegin', '<p aria-role="alert" id="different_password_error" class="form__error">Le password inserite sono differenti</p>');
    else if (rpassword.value == password.value && pwdFieldset.querySelector('#different_password_error')) pwdFieldset.querySelector('#different_password_error').remove();
  });
  rpassword.addEventListener('blur', () => {
    if (rpassword.value != password.value && !pwdFieldset.querySelector('#different_password_error')) 
      pwdFieldset.insertAdjacentHTML('afterbegin', '<p aria-role="alert" id="different_password_error" class="form__error">Le password inserite sono differenti</p>');
    else if (rpassword.value == password.value && pwdFieldset.querySelector('#different_password_error')) pwdFieldset.querySelector('#different_password_error').remove();
  });
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

  const newsForm = document.querySelector('#form--insert-news');
  const startDate = newsForm.querySelector('#news__date--start');
  const endDate = newsForm.querySelector('#news__date--end');
  const newsTitle = newsForm.querySelector('#news__title');
  const newsContent = newsForm.querySelector('#news__content');

  startDate.addEventListener('blur', () => {
    if (startDate.value && newsForm.querySelector('#initial_date_empty')) newsForm.querySelector('#initial_date_empty').remove();
    if (startDate.value && endDate.value && new Date(startDate.value) < new Date(endDate.value) && 
        newsForm.querySelector('#datetime_error')) newsForm.querySelector('#datetime_error').remove();
  });
  endDate.addEventListener('blur', () => {
    if (endDate.value && newsForm.querySelector('#final_date_empty')) newsForm.querySelector('#final_date_empty').remove();
    if (startDate.value && endDate.value && new Date(startDate.value) < new Date(endDate.value) && 
        newsForm.querySelector('#datetime_error')) newsForm.querySelector('#datetime_error').remove();
  });
  newsTitle.addEventListener('blur', () => {
    if (endDate.value && newsForm.querySelector('#title_empty')) newsForm.querySelector('#title_empty').remove();
  });
  newsContent.addEventListener('blur', () => {
    if (newsContent.value && newsForm.querySelector('#content_empty')) newsForm.querySelector('#content_empty').remove();
  });



  const offerForm = document.querySelector('#form--insert-offer');
  const offerTitle = offerForm.querySelector('#offer__title');
  const offerContent = offerForm.querySelector('#offer__content');
  const offerDiscountCode = offerForm.querySelector('#offer__discount-code');
  const offerDiscountPercentage = offerForm.querySelector('#offer__discount-percentage');
  const offerEndDate = offerForm.querySelector('#offer__date--end');
  const offerMinPeople = offerForm.querySelector('#offer__min-people');

  offerTitle.addEventListener('blur', () => {
    if (offerEndDate.value && offerForm.querySelector('#offer__error--title')) 
      offerForm.querySelector('#offer__error--title').remove();
  });
  offerContent.addEventListener('blur', () => {
    if (offerContent.value && offerForm.querySelector('#offer__error--content')) 
      offerForm.querySelector('#offer__error--content').remove();
  });
  offerDiscountCode.addEventListener('blur', () => {
    if (offerDiscountCode.value && offerForm.querySelector('#offer__error--discount-code')) 
      offerForm.querySelector('#offer__error--discount-code').remove();
  });
  offerDiscountPercentage.addEventListener('blur', () => {
    if (offerDiscountPercentage.value && offerForm.querySelector('#offer__error--discount-percentage')) 
      offerForm.querySelector('#offer__error--discount-percentage').remove();
  });
  offerEndDate.addEventListener('blur', () => {
    if (offerEndDate.value && offerForm.querySelector('#offer__error--end-date')) 
      offerForm.querySelector('#offer__error--end-date').remove();
  });
  offerMinPeople.addEventListener('blur', () => {
    if (offerMinPeople.value && offerForm.querySelector('#offer__error--min-people')) 
      offerForm.querySelector('#offer__error--min-people').remove();
  });
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
  email.addEventListener('blur', () => {
    if (email.value && !emailRegex.test(email.value) && !loginForm.querySelector('#email_error')) 
      loginForm.insertAdjacentHTML('afterbegin', '<p aria-role="alert" id="email_error" class="form__error">Inserisci un indirizzo email valido</p>');
    else if (emailRegex.test(email.value) && loginForm.querySelector('#email_error')) loginForm.querySelector('#email_error').remove();
  });
  email.addEventListener('focus', () => {
    if (password.value && loginForm.querySelector('#login_error')) loginForm.querySelector('#login_error').remove();
  });
  password.addEventListener('blur', () => {
    if (password.value && password.value.length < 8 && !loginForm.querySelector('#password_error')) 
      loginForm.insertAdjacentHTML('afterbegin', '<p aria-role="alert" id="password_error" class="form__error">La password deve essere lunga almeno 8 caratteri</p>');
    else if (password.value.length >= 8 && loginForm.querySelector('#password_error')) loginForm.querySelector('#password_error').remove();
  });
  password.addEventListener('focus', () => {
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
    if (name.value && pInfoFieldset.querySelector('#name_error')) pInfoFieldset.querySelector('#name_error').remove();
  });
  surname.addEventListener('blur', () => {
    if (surname.value && pInfoFieldset.querySelector('#surname_error')) pInfoFieldset.querySelector('#surname_error').remove();
  });
  email.addEventListener('blur', () => {
    if (email.value && !emailRegex.test(email.value) && !pInfoFieldset.querySelector('#email_error')) 
    pInfoFieldset.insertAdjacentHTML('afterbegin', '<p aria-role="alert" id="email_error" class="form__error">Inserisci un indirizzo email valido</p>');
    else if (emailRegex.test(email.value) && pInfoFieldset.querySelector('#email_error')) pInfoFieldset.querySelector('#email_error').remove();
  });
  birthday.addEventListener('blur', () => {
    if (new Date(birthday.value) < new Date() && pInfoFieldset.querySelector('#birthday_error')) pInfoFieldset.querySelector('#birthday_error').remove();
  });
  password.addEventListener('focus', () => {
    if (pwdFieldset.querySelector('#different_password_error')) pwdFieldset.querySelector('#different_password_error').remove();
  });
  password.addEventListener('blur', () => {
    if (password.value && password.value.length < 8 && !pwdFieldset.querySelector('#password_len_error')) 
      pwdFieldset.insertAdjacentHTML('afterbegin', '<p aria-role="alert" id="password_len_error" class="form__error">La password deve essere lunga almeno 8 caratteri</p>');
    else if (password.value.length >= 8 && pwdFieldset.querySelector('#password_len_error')) pwdFieldset.querySelector('#password_len_error').remove();

    if (rpassword.value != password.value && rpassword.value && !pwdFieldset.querySelector('#different_password_error')) 
      pwdFieldset.insertAdjacentHTML('afterbegin', '<p aria-role="alert" id="different_password_error" class="form__error">Le password inserite sono differenti</p>');
    else if (rpassword.value == password.value && pwdFieldset.querySelector('#different_password_error')) pwdFieldset.querySelector('#different_password_error').remove();
  });
  rpassword.addEventListener('blur', () => {
    if (rpassword.value != password.value && !pwdFieldset.querySelector('#different_password_error')) 
      pwdFieldset.insertAdjacentHTML('afterbegin', '<p aria-role="alert" id="different_password_error" class="form__error">Le password inserite sono differenti</p>');
    else if (rpassword.value == password.value && pwdFieldset.querySelector('#different_password_error')) pwdFieldset.querySelector('#different_password_error').remove();
  });
}




////////////////////////////////////  BUY  ////////////////////////////////////
const buyForm = document.querySelector('.js-buy__form');
if (buyForm) {
  const name = buyForm.querySelector('#name');
  const surname = buyForm.querySelector('#surname');
  const email = buyForm.querySelector('#email');
  const birthday = buyForm.querySelector('#birthday');
  const cardNumberInput = document.querySelector('#numero_carta');
  const cvv = buyForm.querySelector('#cvv');
  const date = buyForm.querySelector('#scadenza_carta');
  const emailRegex = /^(?!.*\.\.)[a-zA-Z0-9]+([._]*[a-zA-Z0-9])*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

  name.addEventListener('blur', () => {
    if (name.value && buyForm.querySelector('#name_error')) buyForm.querySelector('#name_error').remove();
  });
  surname.addEventListener('blur', () => {
    if (surname.value && buyForm.querySelector('#surname_error')) buyForm.querySelector('#surname_error').remove();
  });
  email.addEventListener('blur', () => {
    if (email.value && !emailRegex.test(email.value) && !buyForm.querySelector('#email_error'))
    buyForm.insertAdjacentHTML('afterbegin', '<p aria-role="alert" id="email_error" class="form__error">Inserisci un indirizzo email valido</p>');
    else if (emailRegex.test(email.value) && buyForm.querySelector('#email_error')) buyForm.querySelector('#email_error').remove();
  });
  birthday.addEventListener('blur', () => {
    if (new Date(birthday.value) < new Date() && buyForm.querySelector('#birthday_error')) buyForm.querySelector('#birthday_error').remove();
  });



  const cvvRegex = /^[0-9]{3}$/;
  cvv.addEventListener('blur', () => {
    if (cvvRegex.test(cvv.value) && buyForm.querySelector('#cvv_error')) buyForm.querySelector('#cvv_error').remove();
  });
  cvv.addEventListener('input', () => {
    if (cvv.value.length > 3) cvv.value = cvv.value.slice(0, 3);
  });




  date.addEventListener('blur', validateDate);
  date.addEventListener('input', (e) => {
    if (isNaN(e.data)) date.value = date.value.slice(0, -1);
    if (date.value.length > 5) date.value = date.value.slice(0, 5);
    const value = date.value.replace(/\D/g, '');
    date.value = value.slice(0, 2) + (value.length >= 2 ? '/' + value.slice(2, 4) : '');
  });

  function validateDate() {
    const dateRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
    const [month, year] = date.value.split('/');
    const currentYear = new Date().getFullYear().toString().slice(-2);
    const currentMonth = (new Date().getMonth() + 1).toString().padStart(2, '0');
    if (!dateRegex.test(date) && (year < currentYear || (year === currentYear && month < currentMonth)) && !buyForm.querySelector('#card_date__error'))
      buyForm.insertAdjacentHTML('afterbegin', '<p aria-role="alert" id="card_date__error" class="form__error">Data non valida<p>');
    else if (dateRegex.test(date) && !(year < currentYear || (year === currentYear && month < currentMonth)) && buyForm.querySelector('#card_date__error'))
      buyForm.querySelector('#card_date__error').remove();

    return true;
  }



  cardNumberInput.addEventListener('blur', validateCard);
  cardNumberInput.addEventListener('input', (e) => {
    if (isNaN(e.data)) cardNumberInput.value = cardNumberInput.value.slice(0, -1);
    if (cardNumberInput.value.length > 19) cardNumberInput.value = cardNumberInput.value.slice(0, 19);
    cardNumberInput.value = formatCardNumber(cardNumberInput.value);
  });
  function validateCard() {
    const cardNumber = cardNumberInput.value.replace(/\s/g, '');
    const cardRegex = /^[0-9]{16}$/;
    if (cardRegex.test(cardNumber) && buyForm.querySelector('#card__error'))
      buyForm.querySelector('#card__error').remove();
  }
  function formatCardNumber(cardNumber) {
    return cardNumber.replace(/(\d{4})(?=\d)/g, '$1 ');
  }
}