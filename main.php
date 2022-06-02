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
                <form name="RefineForm" Action="" Method="GET">
                    <div class="RefineRow">
                        <label>Must have in title: </label>
                        <input type="text" name="MustHaveInTitle" placeholder="SingleTerm" id="MustHaveInTitle"/>
                    </div>
                    <input type="button" name="RefineFormSubmit" Value="GO!" onClick="submitRefine(this.form)" id="RefineFormSubmit"/>
                </form>
                <hr>
                <p>3 results</p>
            </nav>
            <main>
                <div class='anchor' id='anchor'></div>
            </main>
            <footer>
                <img src="huaji.gif" class='center'>
            </footer>
        </div>
        <script>
            var index = 0;
            var mustHaveInTitle = "";

            enterAsClick("MustHaveInTitle", "RefineFormSubmit");

            load_more();

            $(window).scroll(function() {
                if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                    load_more();
                }
            }); 

            function nuke_children(id) {
                children = document.getElementById(id).innerHTML = "";
            }

            function enterAsClick(enterItem, clickItem) {
                var input = document.getElementById(enterItem);

                input.addEventListener("keypress", function(event) {
                    if (event.key === "Enter") {
                        event.preventDefault();
                        document.getElementById(clickItem).click();
                    }
                });
            }

            function submitRefine(form) {
                var new_mustHaveInTitle = form.MustHaveInTitle.value;
                if (typeof new_mustHaveInTitle != 'undefined' && new_mustHaveInTitle.length > 0) {
                    mustHaveInTitle = new_mustHaveInTitle;
                    index = 0;
                    nuke_children('anchor');
                    load_more();
                }
            }

            function load_more() {
                $.ajax({
                    url: `loadmore.php?index=${index++}&mustHaveInTitle=${mustHaveInTitle}`,
                    type: "get",
                    beforeSend: function (){
                        $('.ajax-loader').show();
                    },
                    success: function (data) {
                        $(".anchor").append(data);
                    }
                });
            }
        </script>
    </body>
</html>