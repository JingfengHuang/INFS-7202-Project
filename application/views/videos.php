<?php echo form_open_multipart('Videos/upload');?>
    <h2 class="text-center">Upload video</h2>
    <p class="text-center text-muted">(Maximum file size:100MB)</p>
    <p class="text-center text-muted">(Acceptable file type: mp4, mov, wmv, flv, avi and mkv)</p>

    <div class="row justify-content-center col-12 mb-5">
        <div class="col-md-4 col-md-offset-6 centered">
            <?php echo $uploadStatus;?>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Choose video file</span>
                </div>
                <div class="custom-file">
                    <input id="VIDEO_INPUT" class="custom-file-input" type="file" name="uploadVideo" required="required"/> 
                    <label id="VIDEO_FILE" class="custom-file-label">Choose file</label>
                </div>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Video title</span>
                </div>
                <input type="text" class="form-control" name="videotitle" required="required">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Video category</span>
                </div>
                <select class="custom-select" name="videocategory" placeholder="Choose" required="required">
                    <option value="" disabled selected>Select category</option>
                    <option value="Fun">Funny</option>
                    <option value="Life">Life</option>
                    <option value="Game">Game</option>
                    <option value="Webtoon">Webtoon</option>
                    <option value="Fashion">Fashion</option>
                </select>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Video description</span>
                </div>
                <input type="text" class="form-control" name="description" required="required">
            </div>

            <div class="d-flex flex-column justify-content-center align-items-center mb-1">
                <input id="UPLOAD_VIDEO" class="btn btn-primary" type="submit" value="Upload video" />
            </div>

        </div>
    </div>
<?php echo form_close(); ?>

<h3 class='text-center mb-3'>Manage my videos</h3>

<div class='d-flex flex-wrap'>

    <?php

        echo $videos;

    ?>

</div>