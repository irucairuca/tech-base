//ホップアップは、https://uxmilk.jp/41809　を参考にして作った
// const login = document.getElementById("login");
// login.addEventListener("click", event => {
//   console.log("ログイン");

// });


// const newc = document.getElementById("newc");
// newc.addEventListener("click", event => {
//   console.log("新規会員登録");
// });

$("#login").on("click", function(){
  //leftの値 = (ウィンドウ幅 -コンテンツ幅) ÷ 2
  var leftPosition = (($(window).width() - $("#login-dialog").outerWidth(true)) / 2);
  //CSSを変更
  $("#login-dialog").css({"left": leftPosition + "px"});
  //ダイアログを表示する
  $('.container').css('opacity','0.3');
  $("#login-dialog").show();

});
//閉じるボタンで非表示
$(".forms-close").on("click", function(){
  $(this).parents("#login-dialog").hide();
});

$("#newc").on("click", function(){
  //leftの値 = (ウィンドウ幅 -コンテンツ幅) ÷ 2
  var leftPosition = (($(window).width() - $("#newc-dialog").outerWidth(true)) / 2);
  //CSSを変更
  $("#newc-dialog").css({"left": leftPosition + "px"});
  //ダイアログを表示する
  $('.container').css('opacity','0.3');
  $("#newc-dialog").show();

});
//閉じるボタンで非表示
$(".forms-close").on("click", function(){
  $(this).parents("#newc-dialog").hide();
});

