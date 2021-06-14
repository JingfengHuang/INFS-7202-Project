<div class="container">

    <div class="col-6 offset-3">

		<?php echo form_open(base_url().'login/check_login'); ?>

			<h2 class="text-center">Login</h2>       
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Username" required="required" name="username" value=<?php echo $username ?>>
			</div>
			<div class="form-group">
				<input type="password" class="form-control" placeholder="Password" required="required" name="password" value=<?php echo $password ?>>
			</div>
			<div class="form-group">
				<?php echo $error; ?>
			</div>
			<div class='g-recaptcha' data-sitekey='6Lf2h90aAAAAAOn8fb73Qqz4eMmvfxQqSLqdfW0U'></div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-block">Log in</button>
			</div>
			<div class="clearfix">
				<label class="float-left form-check-label"><input type="checkbox", name="remember" <?php echo $isChecked ?>> Remember me</label>
				<a href="<?php echo base_url(); ?>Findpassword" class="float-right">Forget Password?</a>
			</div>
				
		<?php echo form_close(); ?>

		<div class="form-group">
			<label class="form-text text-muted">Do not have account?</label>
			<a href="<?php echo base_url(); ?>Signup" type="button" class="btn btn-outline-primary btn-block">Sign up</a>
		</div>

	</div>

</div>