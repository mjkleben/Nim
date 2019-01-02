<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Stone Nim</title>
        <link href="include/style.css" type="text/css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Baloo" rel="stylesheet">
    </head>
    <body>
        <?php
            // make sure all prior cached data is erased
            session_destroy();
            session_start();
            $_SESSION['problem'] = NULL;
        
            // Check to see if all the fields are inputted correctly
            if((isset($_POST['pile1'])) && (isset($_POST['pile2'])) && (isset($_POST['submit']))){
                $starting_pile1 = $_POST['pile1'];
                $starting_pile2 = $_POST['pile2'];
                if(!is_numeric($starting_pile1) || !is_numeric($starting_pile2)  && (isset($_POST['submit']))){
                    $_SESSION['problem'] = "Check all fields and make sure you've entered valid numbers.";
                }
                else{
                     //Check and see if number of stones is aligned with the rules
                    if(($starting_pile1 <= 0) || ($starting_pile2 <= 0) && (isset($_POST['submit']))){
                        $_SESSION['problem'] = "Check all fields and make sure you've entered valid numbers.";
                    }
                    else{
                        $_SESSION['pile1']  = $_POST['pile1'];
                        $_SESSION['pile2'] = $_POST['pile2'];
                        // First turn. Will change after the first turn.
                        $_SESSION['turn'] = "Your Turn";
                        $_SESSION['first_turn'] = TRUE;

                        //Variable that shows who won
                        $_SESSION['winner'] = NULL;

                        //Variables to keep track of how many stones the computer takes away
                        $_SESSION['taken_1'] = NULL;
                        $_SESSION['taken_2'] = NULL;

                        //Error message
                        $_SESSION['errormsg'] = NULL;

                        header("Location: include/start.php");
                    }
                }
            }
        
        
        ?>
         <video autoplay muted loop class="river_video">
            <source src="include/media/river.mp4" type="video/mp4">
        </video>

        <div class="container">
            <div class="introduction">
                <img class="nim_logo" src="include/media/nim_logo2.png">
                <p>Play a variant of Nim with stones against a computer!</p>
                <h1>Rules</h1>
                <p> 1. Create two piles, each with one or more stones in it. <br>
                    2. You'll alternate turns with the computer. The active player must take one or more stones from each of one or more piles. <br>
                    3. For your first turn, you can only remove 1 to 5 stones from both the first and the second pile. 
                </p>
                <div class="play_form">
                    <form action="" method="post">
                        <input class="input1" autocomplete="off" name="pile1" value="Pile 1" onfocus="if (this.value=='Pile 1') this.value='';"/>
                        <input class ="input2" autocomplete="off"  name="pile2" value="Pile 2" onfocus="if (this.value=='Pile 2') this.value='';" />
                        <button class="submit_button" name="submit" >START PLAYING!</button>
                        <h3><?php echo $_SESSION['problem'] ?></h3>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>