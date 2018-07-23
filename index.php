<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Demo search Service</title>
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <script src="public/js/jquery-3.3.1.min.js" type="text/javascript"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">Demo Soap </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <input type="text" id="search">
                <button type="button" class="btn btn-primary">Search</button>
            </div>
        </div>
        <div class="row" id="loading" style="display: none;">
            <div class="col-md-12">
                <img src="public/image/loading.gif" alt="">
            </div>
        </div>
        <div class="row" id="resutl">
        </div>
    </div>
    <script type="text/javascript">
        $(function(){
            $('button[type="button"]').click(function(){
                let keyword = $('#search').val().trim();
                if(keyword != ''){
                    $.ajax({
                        url: "client.php",
                        type: "POST",
                        data: {key: keyword},
                        beforeSend: function(){
                            $('#loading').show();
                        },
                        success: function(res){
                            $('#loading').hide();

                        }
                    });
                }
            });
        });
    </script>
</body>
</html>