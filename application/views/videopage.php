<h1 class='col-12 text-center mb-3'> <?php echo $video['videotitle'] ?></h1>
<?php
    echo $video_html;
?>

<div class='col-md-8 offset-2 mt-4'>

    <h4>Comments</h4>
    <p class="text-muted">(Plain text only)</p>

    <?php echo form_open(base_url().'Videopage/comment/'); ?>
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Input your comments here" required="required" name="commenttext">
        </div>
        <div class="form-group">
            <label class="form-check-label"><input type="checkbox", name='anonymous'>Anonymous</label>
            <button type="submit" class="btn btn-info btn-block col-4 offset-8">Send</button>
        </div>
    <?php echo form_close(); ?>

    <?php echo $comment_html ?>

</div>