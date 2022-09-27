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
          flash.className = "modal error gap-1 flex flex-al";
        }, 3000);
      }, 400);
    } else if (type === "success") {
      setTimeout(function () {
        flash.className += " active";
        setTimeout(function () {
          flash.className = "modal success gap-1 flex flex-al";
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

function showMessage() {
  let message = document.getElementById("message");
  let angleDown = document.getElementById("fa-angle-down-message");
  let body = document.getElementById("body");
  let container = document.getElementById("container")

  if (message.classList.contains("active")) {
    message.className = "message flex flex-col close ";
    angleDown.className = "fa fa-angle-down close";
    body.style.overflow = "unset";
    container.style.pointerEvents = "unset";
  } else {
    message.classList = "message flex flex-col active";
    angleDown.className = "fa fa-angle-down active";
    body.style.overflow = "hidden";
    container.style.pointerEvents = "none";
  }
}

function updateScroll() {
  var element = document.getElementById("chats-container");
  element.scrollTop = element.scrollHeight;
}

/* developed by achille david and thibaut dusautoir */
