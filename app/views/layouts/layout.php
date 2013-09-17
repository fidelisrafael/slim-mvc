<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Slim MVC <?php if(isset($page_title)) echo "| " . $page_title  ?></title>
        <meta name="description"   content="">
        <meta name="viewport"      content="width=device-width">
        <link rel="stylesheet" href="public/assets/css/main.css">
    </head>
    <body class="<?php echo $page_body ?>">

        <div class="container">
            <?php if(isset($view_filename)) require $view_filename ?>
        </div>
    
        <footer>
            <p><br>
                Made with love <span>♥</span> by 
                <a href="http://defidelis.herokuapp.com" title="Rafael Fidelis">Rafael Fidelis</a>
            </p>
        </footer>

        <script src="public/assets/js/main.js"></script>
    </body>
</html>
