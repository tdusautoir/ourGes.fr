const socket = new WebSocket("ws://localhost:8080/");

socket.onopen = function (e) {
  console.log("Connection established!");
};

//Make the function wait until the connection is made...
function waitForSocketConnection(socket, callback) {
  setTimeout(function () {
    if (socket.readyState === 1) {
      console.log("Connection is made");
      if (callback != null) {
        callback();
      }
    } else {
      console.log("wait for connection...");
      waitForSocketConnection(socket, callback);
    }
  }, 100); //wait 5 milisecond for the connection...
}

let init_socket = (promotion_name) => {
  waitForSocketConnection(socket, function () {
    console.log(promotion_name);

    let data = {
      command: "register_promotion",
      promotion_name: promotion_name,
    };

    socket.send(JSON.stringify(data));
  });
};

$(function () {
  // Gets a reference to the form element
  var form = document.getElementById("chat-form");

  // Adds a listener for the "submit" event.
  form.addEventListener("submit", function (e) {
    e.preventDefault();

    let id_user = $("#id_user").val();
    let img_user = $("#img_user").val();
    let message = $("#chat-message").val();
    let data = {
      command: "send_message",
      user_img: img_user,
      user_id: id_user,
      msg: message,
    };

    socket.send(JSON.stringify(data));
  });
});

socket.onmessage = function (e) {
  console.log(JSON.parse(e.data));

  $(".message__content").removeClass("empty");
  $(".message__content .empty-message").remove();

  let data = JSON.parse(e.data);
  let chat_class = "";

  if (data.from == "me") {
    chat_class = "reverse"; //data is from me
  } else {
    chat_class = ""; //data is from someone else
  }

  let html_data = `<div class='chat ${chat_class} flex gap-1'><div class='chat__usr'><img src='${data.user_img}' alt=''></div><div class='chat__content flex flex-col'><div class='chat__content__name flex flex-al gap-1'><p>${data.from}</p><p>19:46</p></div><div class='chat__content__text pd-1'><p>${data.msg}</p></div></div></div>`;

  $("#chats-container").append(html_data);

  $("#chat-message").val("");

  updateScroll();
};
