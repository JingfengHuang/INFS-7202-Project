<html>
  <head>
          <title><?php echo $title ?></title>
          <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
          <script src="<?php echo base_url(); ?>assets/js/jquery-3.6.0.min.js"></script>
          <script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
          <link rel="stylesheet" type="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
          <script src="https://www.google.com/recaptcha/api.js"></script>
          <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  </head>

  <body>
    <nav class="navbar navbar-expand-lg navbar-light mb-4 shadow-sm" style="background-color: #f2bfe4;">
      
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <ul class="navbar-nav my-1 mr-auto d-flex flex-row align-items-center">

            <li class="nav-item w-25">
              <a class="nav-link" href="<?php echo base_url(); ?>Trending"><img src='<?php echo base_url(); ?>uploads/avatars/crop_logo.png' class="w-75"></a>
            </li>

            <li class="nav-item">
              <a class="mr-4 nav-link <?php echo $trending_active; ?>" href="<?php echo base_url(); ?>Trending">Trending</a>
            </li>

            <li class="nav-item">
              <a class="mr-4 nav-link <?php echo $subscription_active; ?>" href="<?php echo base_url(); ?>Following">Following</a>
            </li>

            <li class="nav-item">
              <a class="mr-4 nav-link <?php echo $favourites_active; ?>" href="<?php echo base_url(); ?>Favourites">Favourites</a>
            </li>

            <li class="nav-item">
              <a class="mr-4 nav-link <?php echo $watchlater_active; ?>" href="<?php echo base_url(); ?>Watchlater">Watch later (<?php echo $watchLaterNumber; ?>)</a>
            </li>

        </ul>

      </div>

      <form class="form-inline my-1 my-lg-0 mx-lg-2">
        <?php echo form_open('ajax'); ?>
          <input class="form-control mr-sm-2" type="search" id="search_text" placeholder="Search for videos" name="search" aria-label="Search">
          <!-- <button class="btn btn-outline-success my-2 my-sm-0" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="25" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
          </button> -->
        <?php echo form_close(); ?>


      <ul class="navbar-nav col-sm-4 col-md-2 col-lg-1">

        <li class="nav-item dropdown">

          <?php if(!$this->session->userdata('logged_in')) : ?>

            <a class="btn bg-transparent" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img class="nav-item rounded-circle img-fluid img-thumbnail" src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_icons8-male-user-100.png>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
              <a class="dropdown-item" href="<?php echo base_url(); ?>Login">Login</a>
            </div>

          <?php endif; ?>
          
          <?php if($this->session->userdata('logged_in')) : ?>

            <a class="btn bg-transparent" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php
              $avatarHEADER = $this->session->userdata('avatarHEADER');
              echo $avatarHEADER;
            ?>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
              <a class="dropdown-item" href="<?php echo base_url(); ?>Profile">Profile</a>
              <a class="dropdown-item" href="<?php echo base_url(); ?>Videos">My videos</a>
              <a class="dropdown-item" href="<?php echo base_url(); ?>Achievement">My achievements</a>
              <a class="dropdown-item" href="<?php echo base_url(); ?>Myfollowing">Manage subscriptions</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="<?php echo base_url(); ?>Login/logout">Log out</a>
            </div>

          <?php endif; ?>
          

        </li>

      </ul>

    </nav>

    <!-- Ajax -->

    <div id="result_container" class="col-10 offset-1 mb-3 card border-light shadow-sm d-none">
      <h4 id="result_header"></h4>
      <div id="result" class="d-flex flex-wrap">

      </div>

    </div>

<script>
  $(document).ready(function() {
    load_data();
      function load_data(query) {
        $.ajax ({
          url:"<?php echo base_url(); ?>ajax/fetch",
          method:"GET",
          data:{query:query},
          success:function(response) {
            $('#result').html("");
            if (response == "") {
              $('#result_container').addClass("d-none");
              $('#result_header').html("Search results");
              $('#result').html(response);
            } else {
              $('#result_container').removeClass("d-none");
              console.log(response);
              var obj = JSON.parse(response);
              if (obj.length > 0) {
                $.each(obj, function(i, val){
                  var video_url = "<?php echo base_url(); ?>/uploads/videos/" + val.filename;
                  var video_page_url = "<?php echo base_url(); ?>/Videopage/index/" + val.id;
                  $('#result_header').html("Search results");

                  $('#result').append(
                    $("<div class='col-md-3'>").append(
                      $("<a>").attr("href", video_page_url).append(
                        $("<video width='100%'>").attr("src",video_url)
                      ),
                      $("<h4>").text(val.videotitle)
                    )
                  )

                })
              } else {
                $('#result').html("Not Found");
              };
            };
          }
        });
      }

      $('#search_text').keyup(function(){
        var search = $(this).val();
        if (search != '') {
          load_data(search);
        } else {
          load_data();
        }

      });
  });

</script>