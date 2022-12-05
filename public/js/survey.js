function showDesc() {
  let desc = document.getElementById("survey__desc");
  let name = document.getElementById("survey-desc-btn");

  if (desc.classList.contains("active")) {
    desc.className = "survey-desc-container close";
    name.className = "survey-desc-btn close";
  } else {
    desc.className = "survey-desc-container active";
    name.className = "survey-desc-btn active";
  }
}

function addOption() {
  let choiceContainer = document.getElementById("choices__container");
  let inputNbChoice = document.getElementById("nb-choice");
  let nbChoice = inputNbChoice.value;

  nbChoice++;

  choiceContainer.insertAdjacentHTML(
    "beforeend",
    `<div id="choice-${nbChoice}" class="survey-choices-input"><input type='text' class='survey__choice__snd' name='choice-${nbChoice}' autocomplete='off' maxlength='50' placeholder='Choice ${nbChoice}'><i onclick="removeOption(${nbChoice})" class="fa fa-xmark choice-close"></i></div>`
  );

  inputNbChoice.value++;
}

function removeOption(choiceId) {
  let inputNbChoice = document.getElementById("nb-choice");
  document.getElementById(`choice-${choiceId}`).remove();

  var choices = document.getElementsByClassName("survey-choices-input");

  for (let i = 1; i <= inputNbChoice.value; i++) {
    if (choices[i]) {
      choices[i].id = `choice-${i + 1}`;
      choices[i].firstChild.name = `choice-${i + 1}`;
      choices[i].firstChild.placeholder = `Choice ${i + 1}`;
      choices[i].lastChild.setAttribute("onclick", `removeOption(${i + 1})`);
    }
  }

  inputNbChoice.value--;
}

function choose(choice) {
  choice.className = "survey__choice survey__choice-check";
  let choices = document.getElementById("survey__choices");
  choices.style.pointerEvents = "none";
  choices.className += " stat";
  setTimeout(() => {
    choice.className = "survey__choice survey__choice-checked";
  }, 800);
}

function submitChoice(event) {
  let formData = new FormData();
  formData.set('choice', event.value);
  fetch("../controller/survey.php?method=response", { method: 'POST', body: formData })
    .then((res) => res.json())
    .then(console.log)
    .catch((e) => { console.error(e) });
}

document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById('form_survey');
  form.addEventListener('submit', function(event){
    event.preventDefault();
    let formData = new FormData(form);

    fetch("../controller/survey.php?method=response", { method: 'POST', body: formData }).then((res) => res.json()).then(console.log);
  })
})
