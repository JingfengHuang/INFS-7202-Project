<div class='container'>

    <h1 class="col-8"><?php echo $nickname ?>'s personal page</h1>

    <div class="card border-light shadow-sm">

        <div class="card-header border-light shadow-sm">
            <div class="form-group">
                <?php echo $error; ?>
                <?php echo $AvatarStatus;?>
            </div>
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a id="PROFILE_GENERAL_NAV" class="nav-link active" aria-current="true">General</a>
                </li>
                <li class="nav-item">
                    <a id="PROFILE_FOLLOWERS_NAV" class="nav-link">Followers</a>
                </li>
                <li class="nav-item">
                    <a id="PROFILE_AVATAR_NAV" class="nav-link">Edit avatar</a>
                </li>
                <li class="nav-item">
                    <a id="PROFILE_INFO_NAV" class="nav-link" tabindex="-1">Edit profile and password</a>
                </li>
            </ul>
        </div>

        <div id="profile_general" class="card-body border-light shadow-sm">

            <h3>General information</h3>
            <div class="d-flex flex-row align-items-center justify-content-between border-light shadow-sm">
                <div class="w-25 d-flex flex-column align-items-center justify-content-center pl-3">
                    <?php echo $general_info_avatarHTML ?>
                    <span class="text-center"><?php echo $username ?></span>
                    <p class="text-muted text-center"><?php echo $follower_number ?> follower<?php echo $isOne ?></p>
                </div>

                <div class="w-75 d-flex flex-column align-items-center text-center justify-content-center">
                    <?php echo $self_intro ?>
                </div>
            </div>

        </div>

        <div id="profile_followers" class="card-body border-light shadow-sm d-none">
            <h3>Manage my followers</h3>
            <div class="d-flex flex-row align-items-center justify-content-start border-light shadow-sm">
                <?php echo $followers_html; ?>
            </div>
        </div>


        <div id="profile_avatar" class="card-body border-light shadow-sm d-none">

            <?php echo form_open_multipart('Profile/uploadAvatar');?>

                <h3>Avatar</h3>

                <div class="d-flex flex-column justify-content-center align-items-center">

                        <?php echo $avatarHTML ?>

                        <div class="input-group mb-3 mt-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Choose avatar</span>
                            </div>
                            <div class="custom-file">
                                <input id="AVATAR_INPUT" class="custom-file-input" type="file" name="avatar" /> 
                                <label id="AVATAR_FILE" class="custom-file-label">Choose file</label>
                            </div>
                        </div>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">X axis and Y axis</span>
                            </div>
                            <input type="number" class="form-control" name="x_axis" placeholder = 0>
                            <input type="number" class="form-control" name="y_axis" placeholder = 0>
                        </div>
                        <span class="text-center text-muted mb-3">By default X and Y axis is 0</span>

                        <div class="d-flex flex-column justify-content-center align-items-center mb-1">
                            <input class="btn btn-primary" type="submit" value="Save" />
                        </div>

                </div>
            <?php echo form_close(); ?>

        </div>


        <div id="profile_info" class="card-body border-light shadow-sm d-none">

            <?php echo form_open(base_url().'Profile/editProfile'); ?>

                <h3>Update your account info</h3>
            
                <div class="form-group">
                    <label class="form-text text-muted">Username</label>
                    <input class="form-control" value=<?php echo $username ?> disabled>
                </div>
                <div class="form-group">
                    <label class="form-text text-muted">Nickname</label>
                    <input type="text" class="form-control" placeholder="Nickname" required="required" name="nickname" value=<?php echo $nickname ?>>
                </div>
                <div class="form-group">
                    <label class="form-text text-muted">Email address</label>
                    <input type="email" class="form-control" placeholder="Email" required="required" name="email" value='<?php echo $email ?>' disabled>
                </div>
                <div class="form-group">
                    <label class="form-text text-muted">Password</label>
                    <input type="password" class="form-control" placeholder="Password" required="required" name="password">
                </div>
                <div class="form-group">
                    <label class="form-text text-muted">Confirm your password</label>
                    <input type="password" class="form-control" placeholder="Confirm your password" required="required" name="confirm_password">
                </div>
                <div class="form-group">
                    <label class="form-text text-muted">Self introduction</label>
                    <input type="text" class="form-control" name="self_intro" placeholder="Write the new introduction here">
                </div>
                <div class="form-group">
                    <label class="form-text text-muted">Twitter Account</label>
                    <input type="text" class="form-control" name="twitter" placeholder="Your twitter account name" value=<?php echo $twitterName; ?>>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
                </div>
                    
            <?php echo form_close(); ?>

        </div>

    </div>
</div>