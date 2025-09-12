let searchForm = document.querySelector('.search-form');
let profileForm = document.querySelector('.profile');
let searchBtn = document.querySelector('#search-btn');
let loginBtn = document.querySelector('#login-btn');

searchBtn.onclick = () => {
    searchForm.classList.toggle('active');
    profileForm.classList.remove('active');
}

loginBtn.onclick = () => {
    profileForm.classList.toggle('active');
    searchForm.classList.remove('active');
}

document.addEventListener('click', (e) => {
    if (!searchForm.contains(e.target) && !searchBtn.contains(e.target)) searchForm.classList.remove('active');
    if (!profileForm.contains(e.target) && !loginBtn.contains(e.target)) profileForm.classList.remove('active');
});
