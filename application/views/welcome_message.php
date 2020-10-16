<!DOCTYPE html>
<html>
<!-- Mirrored from colorlib.com/polygon/adminator/signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 21 Apr 2020 08:55:26 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<!-- /Added by HTTrack -->

<head>
	<title>Sign In</title>
	<style>
		#loader{transition:all .3s ease-in-out;opacity:1;visibility:visible;position:fixed;height:100vh;width:100%;background:#fff;z-index:90000}#loader.fadeOut{opacity:0;visibility:hidden}.spinner{width:40px;height:40px;position:absolute;top:calc(50% - 20px);left:calc(50% - 20px);background-color:#333;border-radius:100%;-webkit-animation:sk-scaleout 1s infinite ease-in-out;animation:sk-scaleout 1s infinite ease-in-out}@-webkit-keyframes sk-scaleout{0%{-webkit-transform:scale(0)}100%{-webkit-transform:scale(1);opacity:0}}@keyframes sk-scaleout{0%{-webkit-transform:scale(0);transform:scale(0)}100%{-webkit-transform:scale(1);transform:scale(1);opacity:0}}
	</style>
	<link href="assets/css/style.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body class="app">
	<div id="loader">
		<div class="spinner"></div>
	</div>
	<script type="96b834f07a68cb7631eb06b6-text/javascript">
		window.addEventListener('load', () => {
		        const loader = document.getElementById('loader');
		        setTimeout(() => {
		          loader.classList.add('fadeOut');
		        }, 300);
		      });
	</script>
	<div class="peers ai-s fxw-nw h-100vh">
		<div class="d-n@sm- peer peer-greed h-100 pos-r bgr-n bgpX-c bgpY-c bgsz-cv" style="background-image:url(assets/static/images/bg.jpg)">
			<div class="pos-a centerXY">
				<div class="bgc-white bdrs-50p pos-r" style="width:120px;height:120px">
					<img class="pos-a centerXY" src="assets/static/images/default_prf.jpg" alt="" height="150" width="150">
				</div>
			</div>
		</div>
		<div class="col-12 col-md-4 peer pX-40 pY-80 h-100 bgc-white scrollable pos-r" style="min-width:320px">
			<h4 class="fw-300 c-grey-900 mB-40">Login</h4>
			<form id="admin_login_form">
				<div class="form-group">
					<label class="text-normal text-dark">Username</label>
					<input type="text" class="form-control" name="uname" placeholder="Username" required="required">
				</div>
				<div class="form-group">
					<label class="text-normal text-dark">Password</label>
					<input type="password" class="form-control" name="psw" placeholder="Password" required="required">
				</div>
				<div class="form-group">
					<div class="peers ai-c jc-sb fxw-nw">
						<!-- <div class="peer">
							<div class="checkbox checkbox-circle checkbox-info peers ai-c">
								<input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
								<label for="inputCall1" class="peers peer-greed js-sb ai-c"><span class="peer peer-greed">Remember Me</span>
								</label>
							</div>
						</div> -->
						<div class="peer">
							<button class="btn btn-primary" id="login">Login</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<script type="96b834f07a68cb7631eb06b6-text/javascript" src="assets/js/vendor.js"></script>
	<script type="96b834f07a68cb7631eb06b6-text/javascript" src="assets/js/bundle.js"></script>
	<script type="bc0f327f64282ab935667475-text/javascript" src="assets/js/custom.js"></script>
	<script src="assets/js/rocket-loader.min.js" data-cf-settings="96b834f07a68cb7631eb06b6-|49" defer=""></script>

	<script type="text/javascript">
		$(document).ready(function(){
		  $("#admin_login_form").submit(function(e) {
		    e.preventDefault(); 
		    var form = $(this);
		    var url = "user_login";
		    $.ajax({
		       type: "POST",
		       url: url,
		       data: form.serialize(), 
		       success: function(data)
		       {          
		          var array = JSON.parse( data );
		          
		          //alert(array['status']);
		          if(array['flag'] == '1'){
		            var password = array['data']['password'];
		            var user_id = array['data']['user_id'];
		            var type = array['data']['type'];
		            if(type == "admin"){
		              window.location.href = 'image-list';
		            }else if(type == "super_admin"){
		              window.location.href = 'super-admin/dashboard';
		            }
		          }
		          else{
		          	alert(array['status']);
		          }
		        }
		     });
		  });
		});
</script>
	</script>
</body>
<!-- Mirrored from colorlib.com/polygon/adminator/signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 21 Apr 2020 08:55:27 GMT -->

</html>