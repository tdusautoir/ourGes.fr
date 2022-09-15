function showForm() {
  let formLogin = document.getElementById("login-form");
  if (formLogin.classList.contains("active")) {
    formLogin.className = "login-form";
  } else {
    formLogin.className += " active";
  }
}

function showSubmenu() {
  let submenu = document.getElementById("dropdown-menu");
  let angleDown = document.getElementById("fa-angle-down");
  if (submenu.classList.contains("active")) {
    submenu.className = "dropdown-menu";
    angleDown.className = "fa fa-angle-down";
  } else {
    submenu.className += " active";
    angleDown.className += " active";
  }
}
