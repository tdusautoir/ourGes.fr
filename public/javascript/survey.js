const form = document.getElementById('form_survey');
const button = document.getElementById('send_response_btn');

form.addEventListener('submit', function (event) {
  event.preventDefault();

  let container = document.querySelector(".dashboard__component__content");
  button.className = "tag tag--click tag--check active";
  container.style.pointerEvents = "none";

  let formData = new FormData(form);
  fetch("../controller/survey.php?method=response", { method: 'POST', body: formData }).then((res) => res.json()).then(console.log);
})