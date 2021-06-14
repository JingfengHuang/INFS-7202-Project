<div class="col-6 offset-3">
    <?php echo form_open(base_url().'Findpassword/check_and_send_email'); ?>
        <h2 class="text-center">Find password</h2>
        <?php echo $error ?>
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Enter username" required="required" name="username" <?php echo $form_status; ?> value=<?php echo $username_value; ?>>
        </div>
        <div class="form-group">
            <input type="email" class="form-control" placeholder="Enter email address" required="required" name="email" <?php echo $form_status; ?> value=<?php echo $email_value; ?>>
        </div>
        <div class="form-group">
			<button type="submit" class="btn btn-primary btn-block" <?php echo $form_status; ?>>Send verification code</button>
		</div>
    <?php echo form_close(); ?>

    <?php echo form_open(base_url().'Findpassword/verify/'.$this->session->userdata('verificationCode')); ?>
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Verification code" required="required" name="verificationCode">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" placeholder="Enter new password" required="required" name="password">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" placeholder="Confirm new password" required="required" name="confirm_password">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Confirm</button>
        </div>
    <?php echo form_close(); ?>
</div>