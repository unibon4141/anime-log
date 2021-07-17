// ajax
$(function () {
  const $titleButton = $(".myanime-add");
  $titleButton.on("click", function () {
    const title = $(this).data("title");
    $.ajax({
      type: "POST",
      url: "ajax.php",
      data: { title: title },
      dataType: "json",
    })
      .done(function (data) {
        if (data.value) {
          alert("マイアニメに追加しました");
        } else {
          alert("ログインするとマイアニメに追加できるようになります！");
        }
      })
      .fail(function (XMLHttpRequest, textStatus, error) {
        alert(error);
      });
  });
  const $notLoginButton = $(".not-login");
  $notLoginButton.on("click", function () {
    alert("ログインするとマイアニメに追加できるようになります！");
  });
});
