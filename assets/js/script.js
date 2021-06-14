$(document).ready(function(){
    $('#AVATAR_INPUT').change(function(e){
        document.getElementById('AVATAR_FILE').innerHTML = "File has been selected";
    });

    $('#VIDEO_INPUT').change(function(e){
        document.getElementById('VIDEO_FILE').innerHTML = "File has been selected";
    });

    $("#UPLOAD_VIDEO").click(function(){
        alert("It will take some time to upload the video. It depends on the file size and computer's network speed.");
    });

    $('#ACHIEVEMENT_INPUT').change(function(e) {
        document.getElementById('ACHIEVEMENT_FILE').innerHTML = "File has been selected";
    });

    $('#PROFILE_GENERAL_NAV').click(function(){
        $('#PROFILE_GENERAL_NAV').addClass('active');
        $('#PROFILE_AVATAR_NAV').removeClass('active');
        $('#PROFILE_INFO_NAV').removeClass('active');
        $('#PROFILE_FOLLOWERS_NAV').removeClass('active');

        $('#profile_general').removeClass('d-none');
        $('#profile_avatar').addClass('d-none');
        $('#profile_info').addClass('d-none');
        $('#profile_followers').addClass('d-none');
        console.log('success');
    });

    $('#PROFILE_AVATAR_NAV').click(function(){
        $('#PROFILE_GENERAL_NAV').removeClass('active');
        $('#PROFILE_AVATAR_NAV').addClass('active');
        $('#PROFILE_INFO_NAV').removeClass('active');
        $('#PROFILE_FOLLOWERS_NAV').removeClass('active');

        $('#profile_general').addClass('d-none');
        $('#profile_avatar').removeClass('d-none');
        $('#profile_info').addClass('d-none');
        $('#profile_followers').addClass('d-none');
        console.log('success');
    });

    $('#PROFILE_INFO_NAV').click(function(){
        $('#PROFILE_GENERAL_NAV').removeClass('active');
        $('#PROFILE_AVATAR_NAV').removeClass('active');
        $('#PROFILE_INFO_NAV').addClass('active');
        $('#PROFILE_FOLLOWERS_NAV').removeClass('active');

        $('#profile_general').addClass('d-none');
        $('#profile_avatar').addClass('d-none');
        $('#profile_info').removeClass('d-none');
        $('#profile_followers').addClass('d-none');
        console.log('success');
    });

    $('#PROFILE_FOLLOWERS_NAV').click(function(){
        $('#PROFILE_GENERAL_NAV').removeClass('active');
        $('#PROFILE_AVATAR_NAV').removeClass('active');
        $('#PROFILE_INFO_NAV').removeClass('active');
        $('#PROFILE_FOLLOWERS_NAV').addClass('active');

        $('#profile_general').addClass('d-none');
        $('#profile_avatar').addClass('d-none');
        $('#profile_info').addClass('d-none');
        $('#profile_followers').removeClass('d-none');
        console.log('success');
    });

});