<?php
include "core/_startApp.php";

?>
<head>
    <title>Pier</title>
    <link rel="stylesheet" href="/dist/bootstrap-4.0.0-dist/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="/dist/css/custom.css" type="text/css">
    <link rel="icon" href="/pier_512px.png" type="image/png">
    <link href="/dist/fontawesome-free-5.13.0-web/css/all.css" rel="stylesheet">
</head>
<body>

<!--Main Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand col-2 col-sm-3 col-md-2 mr-0" href="/">Pier</a>
    <form class="col p-0 m-0" action="#" id="searchSiteForm" method="get">
        <div class="input-group">
            <input class="form-control form-control-dark" type="text" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button type="submit" class="btn btn-dark"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
    <ul class="navbar-nav px-3 d-none d-xl-block">
        <li class="nav-item text-nowrap text-right">
            <a href="/admin" class="nav-link">Admin</a>
        </li>
    </ul>
</nav>

<!--Main Content-->
<article class="container-fluid">
    <div class="row">
        <!--Sidebar-->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="/" class="nav-link"><i class="fas fa-home"></i> Home</a></li>
                    <li class="nav-item"><a href="/videos" class="nav-link"><i class="fas fa-video"></i> Videos</a></li>
                    <li class="nav-item"><a href="/movies" class="nav-link"><i class="fas fa-film"></i> Movies</a></li>
                    <li>
                        <hr>
                    </li>
                    <li class="nav-item"><a href="/admin/list-files-in-directory?directory=stored%2Fmovies" class="nav-link"><i class="fas fa-film"></i> Movie (TMP)</a></li>
                    <li class="nav-item"><a href="/admin/list-files-in-directory?directory=stored%2Fshows" class="nav-link"><i class="fas fa-video"></i> Shows (TMP)</a></li>
                </ul>
            </div>

            <!--Bottom of sidebar-->
            <div class="bottom-dl_video">
                <div class="p-0 m-0 mb-1 alert"></div>
                <form action="javascript:void(0);" onsubmit="downloadVideo(this);">
                    <div class="input-group">
                        <label for="dlVideoURL" class="sr-only">Download Video URL</label>
                        <input id="dlVideoURL" type="text" class="form-control" placeholder="Enter video URL..." value="">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-fw fa-cloud-download-alt"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </nav>

        <div class="col">
            <main class="container-fluid" id="displayMain">
                <?php include($incFile); ?>
            </main>
        </div>
    </div>
</article>
</body>

<script src="/dist/js/jquery-3.5.0.js"></script>
<script src="/dist/bootstrap-4.0.0-dist/js/bootstrap.min.js"></script>
<script src="/dist/bootstrap-4.0.0-dist/js/popper.min.js"></script>
<script src="/dist/js/core.js"></script>

<script type="text/javascript">
    let timer = null;

    function downloadVideo(ele) {
        event.preventDefault();

        const loadingIcon = "<i class='fas fa-fw fa-circle-notch fa-spin'></i>";
        const dlIcon = "<i class='fas fa-fw fa-cloud-download-alt'></i>";

        const e = $(ele);
        const alertObj = e.siblings(".alert");

        const dlButton = e.find("button[type='submit']");
        const dlURLObj = e.find("input[type='text']");

        let URL = dlURLObj.val();

        if (URL) {
            // Check if URL has proper
            alertObj.slideUp();
            dlButton.prop("disabled", true);
            dlButton.html(loadingIcon);
            $.ajax({
                method: "POST",
                url: "/ajax/download_video",
                data: {
                    url: URL
                }
            }).done(function (data) {
                console.log(data);

                setTimeout(() => {
                    dlButton.prop("disabled", false);
                    dlButton.html(dlIcon);
                    displaySuccess("Media added!", alertObj);
                    dlURLObj.val("");

                    if(timer) {
                        clearTimeout(timer);
                        timer = null;
                    }

                    timer = setTimeout(()=> {
                        alertObj.slideUp();
                    }, 6000)

                }, 3000)
            });

            // Enable the button again



        } else {
            // displayError("Invalid URL", alertObj);
        }
    }

    function displayError(msg, obj) {
        obj.removeClass("alert-success alert-info").addClass("alert-danger");
        obj.removeClass("text-danger text-success text-info").addClass("text-danger");
        obj.html(msg);
        obj.slideDown();
    }

    function displaySuccess(msg, obj) {
        obj.removeClass("alert-danger alert-info").addClass("alert-success");
        obj.removeClass("text-danger text-success text-info").addClass("text-success");
        obj.html(msg);
        obj.slideDown();
    }

</script>

