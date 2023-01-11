sessionStorage.pageNews = 0; //set page news
sessionStorage.pagePlanning = 0; //set page planning

const news_banners = document.getElementsByClassName("dashboard__component__content__banner"); //get news banners
const planning_day = document.getElementsByClassName("day"); //get day planning
const planning_date = document.getElementsByClassName("date"); //get day planning

const pl_arrow_right = document.querySelector(".planning .dashboard__component__title__arrows__arrow--right");
const pl_arrow_left = document.querySelector(".planning .dashboard__component__title__arrows__arrow--left");
const nw_arrow_right = document.querySelector(".news .dashboard__component__title__arrows__arrow--right");
const nw_arrow_left = document.querySelector(".news .dashboard__component__title__arrows__arrow--left");

pl_arrow_left.className += " active";
nw_arrow_left.className += " active";

// news_banners[0].className += " active"; //display the first new
planning_day[0].className += " current"; //display the current day
planning_date[0].className += " current"; //display the current date

function navigateToFollowingNews() {
  if (nw_arrow_left.classList.contains("active")) {
    nw_arrow_left.className = "fa fa-angle-left";
  }

  if (sessionStorage.pageNews < news_banners.length - 1) {
    sessionStorage.pageNews++;
    if (sessionStorage.pagePlanning == news_banners.length - 1) {
      nw_arrow_right.className = "fa fa-angle-left active";
    }
    for (var i = 0; i < news_banners.length; i++) {
      news_banners[i].className = "dashboard__component__content__banner";
    }
    news_banners[sessionStorage.pageNews].className += " active";
  }
}

function navigateToPrecedentNews() {
  ;
  if (nw_arrow_right.classList.contains("active")) {
    nw_arrow_right.className = "fa fa-angle-left";
  }

  if (sessionStorage.pageNews >= 1) {
    sessionStorage.pageNews--;
    if (sessionStorage.pageNews == 0) {
      nw_arrow_left.className = "fa fa-angle-left active";
    }
    for (var i = 0; i < news_banners.length; i++) {
      news_banners[i].className = "dashboard__component__content__banner";
    } ``
    news_banners[sessionStorage.pageNews].className += " active";
  }
}

function navigateToFollowingDay() {
  if (pl_arrow_left.classList.contains("active")) {
    pl_arrow_left.className = "fa fa-angle-left";
  }

  if (sessionStorage.pagePlanning < planning_day.length - 1) {
    sessionStorage.pagePlanning++;
    if (sessionStorage.pagePlanning == planning_day.length - 1) {
      pl_arrow_right.className = "fa fa-angle-left active";
    }
    for (var i = 0; i < planning_day.length; i++) {
      planning_day[i].className = "day";
      planning_date[i].className = "date";
    }
    planning_day[sessionStorage.pagePlanning].className += " current";
    planning_date[sessionStorage.pagePlanning].className += " current";
  }
}

function navigateToPrecedentDay() {
  if (pl_arrow_right.classList.contains("active")) {
    pl_arrow_right.className = "fa fa-angle-left";
  }

  if (sessionStorage.pagePlanning >= 1) {
    sessionStorage.pagePlanning--;
    if (sessionStorage.pagePlanning == 0) {
      pl_arrow_left.className = "fa fa-angle-left active";
    }
    for (var i = 0; i < planning_day.length; i++) {
      planning_day[i].className = "day";
      planning_date[i].className = "date";
    }
    planning_day[sessionStorage.pagePlanning].className += " current";
    planning_date[sessionStorage.pagePlanning].className += " current";
  }
}

/* developed by achille david and thibaut dusautoir */
