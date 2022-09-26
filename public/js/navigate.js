sessionStorage.pageNews = 0; //set page news
const news_banners = document.getElementsByClassName("new-banner"); //get news banners
news_banners[0].style.display = "block";

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

/* developed by achille david and thibaut dusautoir */
