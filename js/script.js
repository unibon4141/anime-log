// ajax
$(function() {
let $titleButton = $('.myanime-add');
let title;
$titleButton.on('click',function(){
  title = $(this).data('title');
  console.log(title);
    $.ajax({
      type:'POST',
      url: 'ajax.php',
      data:{'title' : title
      }
    }).done(function(data){
      alert('マイアニメに追加しました。')
    });
  })
  })