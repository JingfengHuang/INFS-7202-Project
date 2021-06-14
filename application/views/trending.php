<h4 class="text-muted offset-1"><?php echo $now; ?></h2>
<h2 class="text-center">Enjoy your day</h2>

<div class="col-10 offset-1 mb-3 card border-light shadow-sm">
    <div class="card-body">
        <h3 class='ml-2'>Most viewed video</h3>
        <?php

            foreach ($MostPopVideos as $video) {
                echo "<div class='w-100'>

                    <a href=".base_url()."Videopage/index/".$video['id']."><video class='rounded mb-2 border-light shadow-sm' width='100%'><source src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/videos/".$video['filename']." type=".$video['filetype']."></video></a>
                    <h4><a class='text-decoration-none' href=".base_url()."Videopage/index/".$video['id'].">".$video['videotitle']."</a></h4>
                    <div class='row align-items-center w-25'>
                        <img class='rounded-circle w-25 img-thumbnail' src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_".$pop_user_avatar.">
                        <h6 class='text-nowrap ml-3'>".$pop_user_nickname."</h6>
                    </div>
                    <p>Description: ".$video['description']."</p>
                    <p>Upload time: ".$video['time']."</p>
                    <p>".$video['viewedtimes']." views</p>
                    
                </div>";
            }

        ?>
    </div>
</div>

<div class="col-10 offset-1 mb-3 card border-light shadow-sm">
    <h3 class='ml-2'>Trending</h3>
    <div class='d-flex flex-wrap'>
        <?php echo $trending_videos_html ?>
    </div>
</div>

<div class="col-10 offset-1 mb-3 card border-light shadow-sm">
    <h3 class='ml-2'>Funny</h3>
    <div class='d-flex flex-wrap'>
        <?php echo $funny_videos_html ?>
    </div>
</div>

<div class="col-10 offset-1 mb-3 card border-light shadow-sm">
    <h3 class='ml-2'>Life</h3>
    <div class='d-flex flex-wrap'>
        <?php echo $life_videos_html ?>
    </div>
</div>

<div class="col-10 offset-1 mb-3 card border-light shadow-sm">
    <h3 class='ml-2'>Game</h3>
    <div class='d-flex flex-wrap'>
        <?php echo $game_videos_html ?>
    </div>
</div>

<div class="col-10 offset-1 mb-3 card border-light shadow-sm">
    <h3 class='ml-2'>Webtoon</h3>
    <div class='d-flex flex-wrap'>
        <?php echo $webtoon_videos_html ?>
    </div>
</div>

<div class="col-10 offset-1 mb-3 card border-light shadow-sm">
    <h3 class='ml-2'>Fashion</h3>
    <div class='d-flex flex-wrap'>
        <?php echo $fashion_videos_html ?>
    </div>
</div>