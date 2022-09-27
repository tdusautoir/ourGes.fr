function showForm() {
  let formLogin = document.getElementById("login-form");
  if (formLogin.classList.contains("active")) {
    formLogin.className = "login m-0a close";
  } else {
    formLogin.className = "login m-0a active";
  }
}

function showSubmenu() {
  let submenu = document.getElementById("dropdown-menu");
  let angleDown = document.getElementById("fa-angle-down");
  if (submenu.classList.contains("active")) {
    submenu.className = "nav__submenu pd-1 close";
    angleDown.className = "fa fa-angle-down close";
  } else {
    submenu.className = "nav__submenu pd-1 active";
    angleDown.className = "fa fa-angle-down active";
  }
}

function showFlashMessage(type) {
  flash = document.getElementById("flash");
  if (flash) {
    if (type === "error") {
      setTimeout(function () {
        flash.className += " active";
        setTimeout(function () {
          flash.className = "modal error";
        }, 3000);
      }, 400);
    } else if (type === "success") {
      setTimeout(function () {
        flash.className += " active";
        setTimeout(function () {
          flash.className = "modal success";
        }, 3000);
      }, 400);
    }
  }
}

function showPwd() {
  let pwd = document.getElementById("password");
  let eye = document.getElementById("eye");

  if (pwd.type === "password") {
    pwd.type = "text";
    eye.className = "fa fa-eye"
  } else {
    pwd.type = "password"
    eye.className = "fa fa-eye-slash"
  }
}

/* developed by achille david and thibaut dusautoir */
