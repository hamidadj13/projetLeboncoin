let title = document.querySelectorAll(".chat-list-header");
let totalHeight = 0;

for (let i = 0; i < title.length; i++) {
  let totalHeight = 0;
  title[i].addEventListener("click", function () {
    let result = this.nextElementSibling;
    let activeSibling = this.nextElementSibling.classList.contains('active');
    this.classList.toggle('active');
    result.classList.toggle("active");
    if (!activeSibling) {
      for (i = 0; i < result.children.length; i++) {
        totalHeight = totalHeight + result.children[i].scrollHeight + 40;
      }
    } else {
      totalHeight = 0;
    }
    result.style.maxHeight = totalHeight + "px";
  });
}

const themeColors = document.querySelectorAll('.theme-color');

themeColors.forEach(themeColor => {
  themeColor.addEventListener('click', e => {
    themeColors.forEach(c => c.classList.remove('active'));
    const theme = themeColor.getAttribute('data-color');
    document.body.setAttribute('data-theme', theme);
    themeColor.classList.add('active');
  });
});

// Messageries

$(document).ready(function() {
  // Rafraîchir les messages toutes les 2 secondes
  setInterval(function() {
    $("#messages").load("get-messages.php");
  }, 2000);

  // Envoyer un message
  $("#send-message").submit(function(e) {
    e.preventDefault();

    // Validation du formulaire
    let sender = $("#sender").val();
    let receiver = $("#receiver").val();
    let content = $("#content").val();
    let annonce = $("#annonce").val();

    if (sender == "" || receiver == "" || content == "" || annonce == "") {
      alert("Tous les champs sont obligatoires !");
      return false;
    }

    // Envoi du message en AJAX
    $.ajax({
      url: "send-message.php",
      type: "POST",
      data: {
        sender: sender,
        receiver: receiver,
        content: content,
        annonce: annonce
      },
      success: function() {
        $("#content").val("");
        alert("Message envoyé !");
      },
      error: function() {
        alert("Une erreur est survenue, veuillez réessayer plus tard.");
      }
    });
  });
});
