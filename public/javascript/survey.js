const form = document.getElementById('form_survey');
const button = document.getElementById('send_response_btn');
const recent_survey = document.querySelectorAll('.recent__element');

const results = document.querySelectorAll('.results .results__bar__container');
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

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

if(recent_survey.length > 0) {
  recent_survey.forEach(survey => {
    survey.addEventListener('click', function() {
      window.location.href = window.location.href + '?token=' + survey.dataset.token;
    });
  });
}

//refresh survey data
if(urlParams.get('token')) {
  if(results.length > 0) {
    let refresh_survey = setInterval(async () => {
      let response = await fetch('../controller/getSurveyData.php?token='+urlParams.get('token'));

      if(response.ok) {
        let data = await response.json();
        
        //refresh responses
        data.responses.forEach(response => {
          let result = Array.from(results).find((element) => element.dataset.choice_id == response.choice_id);
          if(result) {
            result.querySelector('.results__bar__fill').style.width = response.choice_percentage;
          }
        });

        //refresh users responses
        data.users_info.forEach(user => {
          let result = Array.from(results).find((element) => element.dataset.choice_id == user.choice_id);
          if(result) {
            let images = result.querySelectorAll('.results__bar__container__images img');
            
            //check if he is display
            if(!(Array.from(images).find(image => image.dataset.user_id == user.user_id))) {
              let image_container = result.querySelector('.results__bar__container__images');
              let img_element = document.createElement('img');
              
              img_element.src = user.user_img;
              img_element.title = user.user_name;
              img_element.dataset.user_id = user.user_id;

              image_container.append(img_element);
            }
          }
        });
      } else {
        clearInterval(refresh_survey);
      }
    }, 5000);
  }
}