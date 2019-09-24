<!-- The login-->
<div class="modal fade auth" id="login">
    <div class="modal-dialog modal-dialog-centered">
      	<div class="modal-content">
      
	        <!-- Modal Header -->
	        <div class="modal-header">
	          	<button type="button" class="close" id="close-login" data-dismiss="modal"><i class="material-icons">close</i></button>
	        </div>
	        
	        <!-- Modal body -->
	        <div class="modal-body">
					<h1 class="section-head">Log in to continue</h1>
					<form class="top1">
						<div class="input-group">
		    			<input type="text" class="form-control" placeholder="email address">
		    			<div class="input-group-append">
		    				<span class="input-group-text" id="basic-addon"><i class="fas fa-envelope"></i></span>
		    			</div>
		    		</div>
		          	
					<div class="input-group">
		    			<input type="password" class="form-control" placeholder="password">
		    			<div class="input-group-append">
		    				<span class="input-group-text" id="basic-addon"><i class="fas fa-lock"></i></span>
		    			</div>
		    		</div>

		    		<p class="show-pass">show password</p>

		    		<button class="pink-btn bottom1">login</button>
		    		<a href="#" class="forgot-pass close-login" data-toggle="modal" data-target="#forgot-password">forgot password?</a>
				</form>

				<div class="login-separator">or continue with</div>
				<div class="row">
		    		<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 bottom1">
		    			<a href="#" class="social-btn"><i class="fab fa-facebook-f"></i>facebook</a>
		    		</div>
		    		<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 bottom1">
		    			<a href="#" class="social-btn"><i class="fab fa-google"></i>google</a>
		    		</div>
		    	</div>
		    	<p class="line"></p>
		    	<h4 class="m-0 text-center captalize">Don't have an account? <a href="#" class="bold-cls close-login" data-toggle="modal" data-target="#signup"> Sign up</a></h4>
	        </div>
	        
      	</div>
    </div>

</div>

<!-- The forgot-->
<div class="modal fade auth" id="forgot-password">
    <div class="modal-dialog modal-dialog-centered">
      	<div class="modal-content">
      
	        <!-- Modal Header -->
	        <div class="modal-header">
	          	<button type="button" class="close" id="close-forgot" data-dismiss="modal"><i class="material-icons">close</i></button>
	        </div>
	        
	        <!-- Modal body -->
	        <div class="modal-body">
					<h1 class="section-head">reset password</h1>
					<p class="small-line"></p>
					<h4>Enter the email address associated with your account, and we'll email you a link to reset your password.</h4>
					<form class="top1">
						<div class="input-group">
		    			<input type="text" class="form-control" placeholder="email address">
		    			<div class="input-group-append">
		    				<span class="input-group-text" id="basic-addon"><i class="fas fa-envelope"></i></span>
		    			</div>
		    		</div>

		    		<div class="row">
		    			<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
		    				<a href="#" class="back-to-login close-forgot" href="#" data-toggle="modal" data-target="#login"><i class="fas fa-chevron-left"></i>&nbsp;&nbsp;back to login</a>
		    			</div>
		    			<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
		    				<button class="pink-btn bottom1">send reset link</button>
		    			</div>
		    		</div>
				</form>

	        </div>
	        
      	</div>
    </div>

</div>

<!-- The signup -->
<div class="modal fade auth" id="signup">
    <div class="modal-dialog modal-dialog-centered">
      	<div class="modal-content">
      
	        <!-- Modal Header -->
	        <div class="modal-header">
	          	<button type="button" class="close" id="close-signup" data-dismiss="modal"><i class="material-icons">close</i></button>
	        </div>
	        
	        <!-- Modal body -->
	        <div class="modal-body">
					<h1 class="section-head">welcome to airbnb</h1>
					<form class="top1">
						<div class="input-group">
		    			<input type="text" class="form-control" placeholder="username">
		    			<div class="input-group-append">
		    				<span class="input-group-text" id="basic-addon"><i class="fas fa-lock"></i></span>
		    			</div>
		    		</div>

						<div class="input-group">
		    			<input type="text" class="form-control" placeholder="email address">
		    			<div class="input-group-append">
		    				<span class="input-group-text" id="basic-addon"><i class="fas fa-envelope"></i></span>
		    			</div>
		    		</div>
		          	
					<div class="input-group">
		    			<input type="password" class="form-control" placeholder="password">
		    			<div class="input-group-append">
		    				<span class="input-group-text" id="basic-addon"><i class="fas fa-lock"></i></span>
		    			</div>
		    		</div>

		    		<p class="show-pass">show password</p>

		    		<button class="pink-btn">Signup</button>
				</form>

				<div class="login-separator">or continue with</div>
				<div class="row">
		    		<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 bottom1">
		    			<a href="#" class="social-btn"><i class="fab fa-facebook-f"></i>facebook</a>
		    		</div>
		    		<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 bottom1">
		    			<a href="#" class="social-btn"><i class="fab fa-google"></i>google</a>
		    		</div>
		    	</div>
		    	<p class="line"></p>
		    	<h4 class="m-0 text-center captalize">Already have an Airbnb account? <a href="#" class="bold-cls close-signup" data-toggle="modal" data-target="#login"> Log in</a></h4>
	        </div>
	        
      	</div>
    </div>

</div>