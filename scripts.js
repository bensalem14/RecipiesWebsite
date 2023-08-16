$(document).ready(function () {
  /*function sendEmail() {
    let user = document.getElementById("sendername").innerText;
    let usermail = document.getElementById("sendermail").innerText;
    let usersubject = document.getElementById("sendersubject").innerText;
    let usermessage = document.getElementById("sendermessage").innerText;

    Email.send({
      Host: "smtp.gmail.com",
      Username: user,
      To: "canonisensys14@gmail.com",
      Password: "lbp6020SM",
      From: usermail,
      Subject: usersubject,
      Body: usermessage,
    }).then(function (message) {
      alert("mail sent successfully");
    });
  }*/
  $("#nbingredientsbtn").on("click", function () {
    let x = $("#nbIngredients").val();
    for (let i = 0; i < x; i++)
      $("#ingredientcontainer").append(
        '<input type="text" placeholder="nom ingredient' +
          (i + 1) +
          '" id="nbIngredients" name="ingredints[]" class="form-control" required/> <input type="nomber" placeholder="quantité ' +
          (i + 1) +
          '" id="nbIngredients" name="quantite[]" class="form-control" required/> <input type="text" placeholder="unité' +
          (i + 1) +
          ' g / ml " id="nbIngredients" name="unite[]" class="form-control"  value=""/> <input type="checkbox" placeholder="healthy' +
          (i + 1) +
          '" id="nbIngredients" name="healthy[]" value="' +
          i +
          '"  class="form-check-input" /><label >Healthy</label>'
      );
  });
  $("#nbetapesbtn").on("click", function () {
    let x = $("#nbetapes").val();
    for (let i = 0; i < x; i++)
      $("#etapetcontainer").append(
        '<input type="text"  name="etapes[]" class="form-control" required/>'
      );
  });
});
