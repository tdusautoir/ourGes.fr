sessionStorage.pageNews = 0; //set page news
sessionStorage.pagePlanning = 0; //set page planning

const news_banners = document.getElementsByClassName("news__banner"); //get news banners
const planning_day = document.getElementsByClassName("day"); //get day planning

news_banners[0].style.display = "block"; //display the first new
planning_day[0].style.display = "block"; //display the current day

function navigateToFollowing() {
  if (sessionStorage.pageNews < news_banners.length - 1) {
    sessionStorage.pageNews++;
    for (var i = 0; i < news_banners.length; i++) {
      news_banners[i].style.display = "none";
    }
    news_banners[sessionStorage.pageNews].style.display = "block";
  }
}

function navigateToPrecedent() {
  if (sessionStorage.pageNews >= 1) {
    sessionStorage.pageNews--;
    for (var i = 0; i < news_banners.length; i++) {
      news_banners[i].style.display = "none";
    }
    news_banners[sessionStorage.pageNews].style.display = "block";
  }
}

function navigateToFollowingDay() {
  if (sessionStorage.pagePlanning < planning_day.length - 1) {
    sessionStorage.pagePlanning++;
    for (var i = 0; i < planning_day.length; i++) {
      planning_day[i].style.display = "none";
    }
    planning_day[sessionStorage.pagePlanning].style.display = "block";
  }
}

function navigateToPrecedentDay() {
  if (sessionStorage.pagePlanning >= 1) {
    sessionStorage.pagePlanning--;
    for (var i = 0; i < planning_day.length; i++) {
      planning_day[i].style.display = "none";
    }
    planning_day[sessionStorage.pagePlanning].style.display = "block";
  }
}

/* developed by achille david and thibaut dusautoir */
