<div class="container">

    <div class="col-6 offset-3">

		<h2 class="text-center">Create your acount</h2>

		<?php echo $verify_message ?>

		<?php echo form_open(base_url().'Signup/send_verification'); ?>
			<div class="form-inline w-100">
				<div class="form-group w-75">
					<input type="email" class="form-control w-75" <?php echo $form_status ?> placeholder="Email" required="required" name="verication_email" value=<?php echo $verification_email_address; ?>>
				</div>
				<div class="form-group w-25">
					<button type="submit" class="btn btn-primary btn-block" <?php echo $form_status ?>>Confirm</button>
				</div>
			</div>
		<?php echo form_close(); ?>

		<?php echo form_open(base_url().'Signup/canVerify'); ?>
			<div class="form-inline w-100">
				<div class="form-group w-75">
					<input type="text" class="form-control w-75" <?php echo $form_status ?> placeholder="Verification code" required="required" name="token">
				</div>
				<div class="form-group w-25">
					<button type="submit" class="btn btn-primary btn-block" <?php echo $form_status ?>>Verify</button>
				</div>
			</div>
		<?php echo form_close(); ?>
		
		<?php echo form_open(base_url().'Signup/check_signup'); ?>

			<div class="form-group">
				<input type="text" class="form-control" placeholder="Username" required="required" name="username">
			</div>
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Nickname" required="required" name="nickname">
			</div>
			<div class="form-group">
				<input type="password" class="form-control" placeholder="Password" required="required" name="password">
			</div>
			<div class="form-group">
				<input type="password" class="form-control" placeholder="Confirm your password" required="required" name="confirm_password">
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-block">Register</button>
			</div>
				
		<?php echo form_close(); ?>

	</div>

</div>