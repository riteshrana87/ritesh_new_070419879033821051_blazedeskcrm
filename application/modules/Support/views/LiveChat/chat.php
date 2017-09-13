<?php
//$myid='1';
//$fid='2';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Chat</title>

<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<div class="whitebox pad-10 modal-dialog"  >
	<div class="container">
    <h4>Chat history</h4>
      <div class="bd-livechat-box" id="chat" >
      
        <div class="stream" id="cstream" >
		
      </div>
      </div>
      <div class="msg">
		
          <form method="post" id="msenger" action="">
            <div class="form-group chatbox-sendmsg"><textarea name="msg" id="msg-min" class="form-control" rows="1"></textarea>
            <input type="hidden" name="mid" value="<?php echo $fid."to";?>">
            <input type="hidden" name="fid" value="<?php echo $myid."from";?>">
            <i class="fa fa-paper-plane"></i>
            <input type="submit" value="" id="sb-mt" class=" btn btn-blue"></div>
          </form>
      </div>
      <div id="dataHelper" last-id=""></div>
  </div>
  </div>
<script type="text/javascript">
$(document).keyup(function(e){
	if(e.keyCode == 13){
		if($('#msenger textarea').val().trim() == ""){
			$('#msenger textarea').val('');
		}else{
			$('#msenger textarea').attr('readonly', 'readonly');
			$('#sb-mt').attr('disabled', 'disabled');	// Disable submit button
			sendMsg();
		}		
	}
});	

$(document).ready(function() {
    $('#msg-min').focus();
	$('#msenger').submit(function(e){
		$('#msenger textarea').attr('readonly', 'readonly');
		$('#sb-mt').attr('disabled', 'disabled');	// Disable submit button
		sendMsg();
		e.preventDefault();	
	});
});

function sendMsg(){
	$.ajax({
		type: 'post',
		url: 'LiveChat/chatM?rq=new',
		data: $('#msenger').serialize(),
		dataType: 'json',
		success: function(rsp){
				//alert(rsp);
				//console.log(rsp);
				$('#msenger textarea').removeAttr('readonly');
				$('#sb-mt').removeAttr('disabled');	// Enable submit button
				if(parseInt(rsp.status) == 0){
					alert(rsp.msg);
				}else if(parseInt(rsp.status) == 1){
					$('#msenger textarea').val('');
					$('#msenger textarea').focus();
					//$design = '<div>'+rsp.msg+'<span class="time-'+rsp.lid+'"></span></div>';
					$design = '<div class="float-fix bd-livechat-content">'+
									'<div class="m-rply">'+
										'<div class="msg-bg">'+
											'<div class="msgA"><span>'+
												rsp.fromname+':</span>'+rsp.msg+
												'<div class="bd-chat-pos">'+
													'<div class="msg-time time-'+rsp.lid+'"></div>'+
													'<div class="myrply-i"></div>'+
												'</div>'+
											'</div>'+
										'</div>'+
									'</div>'+
								'</div>';
					$('#cstream').append($design);

					$('.time-'+rsp.lid).livestamp();
					$('#dataHelper').attr('last-id', rsp.lid);
					$('#chat').scrollTop($('#cstream').height());
				}
			}
		});
}
function checkStatus(){
	//alert($('#chat').height());
	var chat_heigt = $('#chat').height();
	if(chat_heigt>235){
		
		
		$('#chat').css({"overflow":"hidden","overflow-y":"scroll","max-height": "235px"}); 
		
		
	}
	$fid = '<?php echo $fid; ?>';
	$mid = '<?php echo $myid; ?>';
	$.ajax({
		
		type: 'post',
		url: 'LiveChat/chatM?rq=msg',
		data: {fid: $fid, mid: $mid, lid: $('#dataHelper').attr('last-id')},
		dataType: 'json',
		cache: false,
		success: function(rsp){
				
				if(parseInt(rsp.status) == 0){
					
					return false;
				}else if(parseInt(rsp.status) == 1){
					
					getMsg();
				}
			}
		});	
}

// Check for latest message
setInterval(function(){checkStatus();}, 5000);

function getMsg(){
	
	$fid = '<?php echo $fid; ?>';
	$mid = '<?php echo $myid; ?>';
	$.ajax({
		type: 'post',
		url: 'LiveChat/chatM?rq=NewMsg',
		data:  {fid: $fid, mid: $mid},
		dataType: 'json',
		success: function(rsp){
				if(parseInt(rsp.status) == 0){
					//alert(rsp.msg);
				}else if(parseInt(rsp.status) == 1){
					$design = '<div class="float-fix bd-livechat-content">'+
									'<div class="f-rply">'+
										'<div class="msg-bg">'+
											'<div class="msgA"><span>'+
												rsp.fromname+':</span>'+rsp.msg+
												'<div class="bd-chat-pos">'+
													'<div class="msg-time time-'+rsp.lid+'"></div>'+
													'<div class="myrply-f"></div>'+
												'</div>'+
											'</div>'+
										'</div>'+
									'</div>'+
								'</div>';
					$('#cstream').append($design);
					$('#chat').scrollTop ($('#cstream').height());
					$('.time-'+rsp.lid).livestamp();
					$('#dataHelper').attr('last-id', rsp.lid);	
				}
			}
	});
}
</script>
</body>
</html>