function login() {
    let form = document.querySelector('.login');
    form.classList.toggle("login--active");
}

function logout() {
    let modal = document.getElementById("logout");
    let arrow = document.querySelector('.header__buttons i');
    modal.classList.toggle("logout--active")
    arrow.classList.toggle("active");
}

function password() {
    let eye = document.getElementById("eye");
    let input = document.getElementById("password");
    if (eye.className === "fa fa-eye-slash") {
        eye.className = "fa fa-eye";
        input.type = "text";
    } else {
        eye.className = "fa fa-eye-slash";
        input.type = "password";
    }
}

function class__modal() {
    let modal = document.getElementById("class__modal");
    let dahsboard = document.querySelector('.dashboard');
    modal.classList.toggle("active");
}

function add__option() {
    let choiceContainer = document.getElementById("creation__answers");
    let inputNbChoice = document.getElementById("nb-choice");
    let nbChoice = inputNbChoice.value;

    nbChoice++;

    choiceContainer.insertAdjacentHTML(
        "beforeend",
        `<div id="choice-${nbChoice}" class="creation__answers__input"><input type="text" placeholder="option ${nbChoice} " name='choice-${nbChoice}'><i class="fa fa-xmark" onclick="delete__option(${nbChoice})"></i></div>`
    );

    inputNbChoice.value++;
}

function delete__option(choiceId) {
    let inputNbChoice = document.getElementById("nb-choice");

    document.getElementById(`choice-${choiceId}`).remove();

    var choices = document.getElementsByClassName("creation__answers__input");

    for (let i = 2; i <= inputNbChoice.value; i++) {
        if (choices[i]) {
            choices[i].id = `choice-${i + 1}`;
            choices[i].firstChild.placeholder = `option ${i + 1}`;
            choices[i].lastChild.setAttribute("onclick", `delete__option(${i + 1})`);
        }
    }

    inputNbChoice.value--;
}

function submit(button) {
    let container = document.querySelector(".dashboard__component__content");
    button.className = "tag tag--click tag--check active";
    container.style.pointerEvents = "none";
}

function copy__link() {
    let input = document.querySelector('.share input');
    navigator.clipboard.writeText(input.value);
    setTimeout(function () {
        input.classList.toggle("share--active");
    }, 3000);
}

function get_class_info(classId) {
    let modal = document.getElementById("class__modal");
    let url = "./controller/getClassInfo.php?classId=" + classId;

    fetch(url).then((result) => result.json()).then((jsondata) => {
        if (jsondata.error != undefined) {
            window.location.reload();
        }

        let data = jsondata[0];
        let date = jsondata[1];

        modal.querySelector('.class__title').innerHTML = data.name;
        modal.querySelector('.professor').innerHTML = data.teacher;
        modal.querySelector('.room').innerHTML = data.rooms[0].name;
        modal.querySelector('.stage').innerHTML = data.rooms[0].floor;
        modal.querySelector('.date').innerHTML = date[0] + ' - ' + date[1];
        modal.querySelector('.modality').innerHTML = data.modality
        modal.querySelector('.campus').innerHTML = data.rooms[0].campus

        class__modal();
    });
}