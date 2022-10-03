sessionStorage.pageNews = 0; //set page news
sessionStorage.pagePlanning = 0; //set page planning

const news_banners = document.getElementsByClassName("news__banner"); //get news banners
const planning_day = document.getElementsByClassName("day"); //get day planning
const planning_date = document.getElementsByClassName("date-container"); //get day planning

const pl_nxt = document.getElementById("pl-nxt");
const pl_lst = document.getElementById("pl-lst");

pl_lst.className += " active";

news_banners[0].className += " active"; //display the first new
planning_day[0].className += " current"; //display the current day
planning_date[0].className += " current"; //display the current date

function navigateToFollowing() {
  if (sessionStorage.pageNews < news_banners.length - 1) {
    sessionStorage.pageNews++;
    for (var i = 0; i < news_banners.length; i++) {
      news_banners[i].className = "news__banner pd-1";
    }
    news_banners[sessionStorage.pageNews].className += " active";
  }
}

function navigateToPrecedent() {
  if (sessionStorage.pageNews >= 1) {
    sessionStorage.pageNews--;
    for (var i = 0; i < news_banners.length; i++) {
      news_banners[i].className = "news__banner pd-1";
    }
    news_banners[sessionStorage.pageNews].className += " active";
  }
}

function navigateToFollowingDay() {
  if (pl_lst.classList.contains("active")) {
    pl_lst.className = "fa fa-angle-down";
  }

  if (sessionStorage.pagePlanning < planning_day.length - 1) {
    sessionStorage.pagePlanning++;
    console.log(sessionStorage.pagePlanning);
    if (sessionStorage.pagePlanning == planning_day.length - 1) {
      pl_nxt.className = "fa fa-angle-down active";
    }
    for (var i = 0; i < planning_day.length; i++) {
      planning_day[i].className = "day";
      planning_date[i].className = "date-container";
    }
    planning_day[sessionStorage.pagePlanning].className += " current";
    planning_date[sessionStorage.pagePlanning].className += " current";
  }
}

function navigateToPrecedentDay() {
  if (pl_nxt.classList.contains("active")) {
    pl_nxt.className = "fa fa-angle-down";
  }

  if (sessionStorage.pagePlanning >= 1) {
    sessionStorage.pagePlanning--;
    console.log(sessionStorage.pagePlanning);
    if (sessionStorage.pagePlanning == 0) {
      pl_lst.className = "fa fa-angle-down active";
    }
    for (var i = 0; i < planning_day.length; i++) {
      planning_day[i].className = "day";
      planning_date[i].className = "date-container";
    }
    planning_day[sessionStorage.pagePlanning].className += " current";
    planning_date[sessionStorage.pagePlanning].className += " current";
  }
}

/* developed by achille david and thibaut dusautoir */
