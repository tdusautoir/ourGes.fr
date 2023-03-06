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

function copy__link() {
    let input = document.querySelector('.share input');
    navigator.clipboard.writeText(input.value);
    input.classList.toggle("share--active");
    setTimeout(function () {
        input.classList = "";
    }, 1000);
}

function loading() {
    let loading = document.querySelector('.login');
    loading.classList.toggle("login--loading");
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

        //reset modal
        modal.querySelector('.room').parentNode.classList.remove('empty');
        modal.querySelector('.stage').parentNode.classList.remove('empty');
        modal.querySelector('.campus').parentNode.classList.remove('empty');
        modal.querySelector('.professor').parentNode.classList.remove('empty');
        modal.querySelector('.modality').parentNode.classList.remove('empty');

        modal.querySelector('.class__title').innerHTML = data.name;
        modal.querySelector('.class__date').innerHTML = date[0] + ' - ' + date[1];

        if(data.teacher && !(data.teacher.trim() == '')) {
            if(!(data.teacher.trim() == '')) {
                modal.querySelector('.professor').innerHTML = data.teacher;
            } else {
                modal.querySelector('.professor').parentNode.classList.add('empty');
            }
        } else {
            modal.querySelector('.professor').parentNode.classList.add('empty');
        }

        if(data.rooms) {
            if(!(data.rooms[0].name.trim() == '')) {
                modal.querySelector('.room').innerHTML = data.rooms[0].name;
            } else {
                modal.querySelector('.room').parentNode.classList.add('empty');
            }

            if(!(data.rooms[0].floor.trim() == '')) {
                modal.querySelector('.stage').innerHTML = data.rooms[0].floor;
            } else {
                modal.querySelector('.stage').parentNode.classList.add('empty');
            }

            if(!(data.rooms[0].campus.trim() == '')) {
                modal.querySelector('.campus').innerHTML = data.rooms[0].campus
            } else {
                modal.querySelector('.campus').parentNode.classList.add('empty');
            }
        } else {
            modal.querySelector('.room').parentNode.classList.add('empty');
            modal.querySelector('.stage').parentNode.classList.add('empty');
            modal.querySelector('.campus').parentNode.classList.add('empty');
        }

        if(data.modality) {
            if(!(data.modality.trim() == '')) { 
                modal.querySelector('.modality').innerHTML = data.modality
            } else {
                modal.querySelector('.modality').parentNode.classList.add('empty');
            }
        } else {
            modal.querySelector('.modality').parentNode.classList.add('empty');
        }

        class__modal();
    });
}

function notif() {
    let notif = document.querySelector('.notif');
    if(notif) {
        setTimeout(function () {
            notif.classList.toggle('close');
        }, 2500);
    }
}

function setSemester(option) {
    const absences = document.querySelectorAll('.absence .dashboard__component__content__lign');
    const courses = document.querySelectorAll('.classes .dashboard__component__content__lign');
    const grades = document.querySelectorAll('.marks .dashboard__component__content__lign');

    const containers = [absences, courses, grades];

    containers.forEach(elements => {
        elements.forEach(element => {
            if(parseInt(option.value)) {
                if(element.dataset.semester !== option.value) {
                    element.classList.add("d-none");
                } else {
                    element.classList.remove("d-none");
                }
            } else {
                element.classList.remove("d-none");
            }
        })
    });

    const semester_indicators = document.querySelectorAll('.dashboard__component__content__lign__trimester');

    if(semester_indicators.length > 0) {
        for(let i = 0; i < semester_indicators.length; i++) {
            semester_indicators[i].classList.add('d-none');
        }
    }
}