<h2 class='text-center mb-5'>Achievements</h2>

<div class='d-flex flex-column justify-content-center align-items-center col-10 offset-1'>
    <h5 class=" mb-5"><?php echo $welcome; ?></h5>

    <div class='d-flex flex-column justify-content-start align-items-center w-75 mb-5'>
        <ul class="d-flex flex-column justify-content-start align-items-center list-group w-75 shadow">

            <li class="list-group-item d-flex justify-content-between align-items-start w-100">
                <div class="ms-2 me-auto">
                    <h5 class="fw-bold">Leave <?php echo $commentCounter; ?> comments</h5>
                    More comments than <?php echo $commentUserPercentage?>% of all users
                </div>
                <span class="badge bg-primary rounded-pill text-white"><?php echo $commentTitle; ?></span>
            </li>

            <li class="list-group-item d-flex justify-content-between align-items-start w-100">
                <div class="ms-2 me-auto">
                    <h5 class="fw-bold">Have <?php echo $favouriteCounter; ?> favourite videos</h5>
                    More favourite videos than <?php echo $favouritePercentage?>% of all users
                </div>
                <span class="badge bg-success rounded-pill text-white"><?php echo $favouriteTitle; ?></span>
            </li>

            <li class="list-group-item d-flex justify-content-between align-items-start w-100">
                <div class="ms-2 me-auto">
                    <h5 class="fw-bold">Following <?php echo $followCounter; ?> users</h5>
                    Following more than <?php echo $followPercentage?>% of all users
                </div>
                <span class="badge bg-info rounded-pill text-white"><?php echo $followTitle; ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-start w-100">
                <div class="ms-2 me-auto">
                    <h5 class="fw-bold">Gave <?php echo $likeCounter; ?> likes</h5>
                    More than <?php echo $likePercentage?>% of all users
                </div>
                <span class="badge bg-warning rounded-pill text-white"><?php echo $likeTitle; ?></span>
            </li>
        </ul>
    </div>

    <?php echo $error; ?>
    <?php echo form_open_multipart('Achievement/makeAchievement');?>
        <h4 class="text-center">Make your achievement image</h4>
        <p class="text-center text-muted">(Maximum file size:100MB)</p>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Choose achievement background</span>
            </div>
            <div class="custom-file">
                <input id="ACHIEVEMENT_INPUT" class="custom-file-input" type="file" name="background" required="required"> 
                <label id="ACHIEVEMENT_FILE" class="custom-file-label">Choose file</label>
            </div>
        </div>

        <div class="d-flex flex-column justify-content-center align-items-center mb-1">
            <input id="UPLOAD_BACKGROUND" class="btn btn-primary" type="submit" value="Make achievement image" />
        </div>
    <?php echo form_close(); ?>

</div>