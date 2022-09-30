$(function () {
  const socket = new WebSocket("ws://localhost:8080/");

  socket.onopen = function (e) {
    console.log("Connection established!");
  };

  $("#chat-form").on("submit", function (e) {
    e.preventDefault();

    let id_user = $("#id_user").val();
    let message = $("#chat-message").val();
    let name_promotion = $("#name_promo").val();
    let data = {
      user_id: id_user,
      msg: message,
      promotion_name: name_promotion,
    };

    socket.send(JSON.stringify(data));
  });

  socket.onmessage = function (e) {
    console.log(JSON.parse(e.data));

    let data = JSON.parse(e.data);
    let chat_class = "";

    if (data.from == "Me") {
      chat_class = "reverse"; //data is from me
    } else {
      chat_class = ""; //data is from someone else
    }

    let html_data = `<div class='chat ${chat_class} flex gap-1'><div class='chat__usr'><img src='./img/logo.png' alt=''></div><div class='chat__content flex flex-col'><div class='chat__content__name flex flex-al gap-1'><p>${data.from}</p><p>19:46</p></div><div class='chat__content__text pd-1'><p>${data.msg}</p></div></div></div>`;

    $("#chats-container").append(html_data);

    $("#chat-message").val("");

    updateScroll();
  };
});
