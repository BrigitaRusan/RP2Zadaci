<div class = 'wrapper'>
    <?php require_once __DIR__."/login_header.php";?>
    <div class = 'margin-auto'>
        <h1 class = 'center'>Join Quack:</h1>
        
        <p><img src="quack.jpg" class='margin-auto' style= "width:200px; height:200px;"></p>
        <p> We need your username and password to sign you up! </p>

        <form action = "quack.php?rt=signup/signup" method = "post" class = 'margin-auto'>
            <input type = "text" name = "username" placeholder = "Username" class = 'login'><br><br>
            <input type = "email" name = "email" placeholder = "Email" class = 'login'><br>
            <input type = "password" name = "password" placeholder = "Password" class = 'login'><br><br>
            <input type = "submit" value = "Register" class = 'submit'>
        </form>

        <?php
        if($losa_registracija)
            echo "<br><p class = 'bold center'>Registracija nije uspjela, pokušajte ponovno!</p>";

        if($registracijski_status)
            echo "<br><p class = 'bold center'>Uspješna registracija! Za ulazak u aplikaciju obavite login.</p>";
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
