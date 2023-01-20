const form = document.getElementById('form_survey');
const button = document.getElementById('send_response_btn');

if (form) {
  form.addEventListener('submit', function (event) {
    event.preventDefault();

    let container = document.querySelector(".dashboard__component__content");
    button.className = "tag tag--click tag--check active";
    container.style.pointerEvents = "none";

    let formData = new FormData(form);
    fetch("../controller/survey.php?method=response", { method: 'POST', body: formData })
      .then((data) => data.json())
      .then((res) => {
        console.log(res);
        setTimeout(() => { 
          if(res.success) {
            window.location.href = window.location.href + "&#results";
          } else {
            window.location.reload(); 
          }
        }, 1600);
      });
  })
}