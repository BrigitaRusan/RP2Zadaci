<div class = 'wrapper'>
    <?php require_once __DIR__."/login_header.php";?>

    <div class = 'margin-auto'>
        <h1 class = 'center'>Sign in to Quack:</h1>
        <p><img src="quack.jpg" class='margin-auto' style= "width:200px; height:200px;"></p>

        <form action = "quack.php?rt=login/login" method = "post" class = 'margin-auto'>
            <input type = "text" name = "username" placeholder = "Username" class = 'login'><br>
            <input type = "password" name = "password" placeholder = "Password" class = 'login'><br><br>
            <input type = "submit" value = "Login" class = 'submit'>
        </form>

        <?php 
        if($los_login) 
            echo "<br><p class = 'bold center'>Login nije uspio, pokušajte ponovno!</p>";
        ?>
    </div>
    <script>
        let body = document.body,
            html = document.documentElement;

        let height = Math.max( body.scrollHeight, body.offsetHeight, 
                       html.clientHeight, html.scrollHeight, html.offsetHeight );
        $("div.wrapper").css("height", height);
    </script>

    <?php require_once __DIR__."/_footer.php";?>
</div>
