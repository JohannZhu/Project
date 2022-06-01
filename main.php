<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Job Hub</title>
        <link rel="stylesheet" href="main.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>   
    </head>
    <body>
        <div class="container">
            <header>
                <img id="logo" src="jobhub.png" height="50px">
            </header>
            <nav>
                <hr>
                <p>3 results</p>
            </nav>
            <main>
                <div class='anchor'></div>
            </main>
            <footer>
                <img src="huaji.gif" class='center'>
            </footer>
        </div>
        <script>
            function load_more(index) {
                $.ajax({
                    url: 'loadmore.php?index=' + index,
                    type: "get",
                    beforeSend: function (){
                        $('.ajax-loader').show();
                    },
                    success: function (data) {
                        $(".anchor").append(data);
                    }
                });
                return ++index;
            }

            var index = load_more(0);

            $(window).scroll(function() {
                if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                    index = load_more(index);
                }
            });
        </script>
    </body>
</html>