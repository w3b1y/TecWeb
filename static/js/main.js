const navMenu = document.querySelector('.nav__menu'),
      navToggle = document.querySelector('.nav__toggle'),
      navClose = document.querySelector('.nav__close'),
      navLink = document.querySelectorAll('.nav__item');

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
  const navMenu = document.querySelector('.nav__menu');
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

const newsCard = document.querySelectorAll('.card__news');
newsCard.forEach(card => {
  let newsExpandButton = card.querySelector('.news__expand');
  let newsDescription = card.querySelector('.news__body-content');
  newsExpandButton.addEventListener('click', () => {
    newsDescription.classList.toggle('show');
    newsExpandButton.classList.toggle('rotate');
    newsExpandButton.classList.contains('rotate') ? 
    newsExpandButton.setAttribute('aria-label', 'Riduci la notizia') : 
    newsExpandButton.setAttribute('aria-label', 'Espandi la notizia');
  });
});