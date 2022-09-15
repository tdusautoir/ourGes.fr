function showForm() {
  let formLogin = document.getElementById("login-form");
  formLogin.className += " active";
}

function showSubmenu() {
  let submenu = document.getElementById("dropdown-menu");
  if (submenu.classList.contains("active")) {
    submenu.className = "dropdown-menu";
  } else {
    submenu.className += " active";
  }
}
