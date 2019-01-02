<html>
        <head>
        </head>
            <title>Nim with Stones!</title>
            <link href="start.css" type="text/css" rel="stylesheet" />
            <link href="https://fonts.googleapis.com/css?family=Baloo" rel="stylesheet">
        <body>
            <script>
                function playagain(){
                    window.location.href = "../index.php";
                }
            </script>

            <h1>This the game page </h1>
            <?php 
                session_start();


                // Variables for keeping track of how many stones are on the screen
                $pile1= $_SESSION['pile1'];
                $pile2= $_SESSION['pile2'];

                //check to see if user inputs are integers and possible given current number of stones
                function isValid($input1, $input2){
                    // make sure fields aren't empty
                    if((!is_numeric($input1) || !is_numeric($input2) )  && (isset($_REQUEST['submit_newpiles']))){
                        return FALSE;
                    }
                    // check to see if the inputs are numbers
                    if(is_numeric($input1) && is_numeric($input2) && isset($_REQUEST['submit_newpiles'])){
                        //Check if it's the player's first turn. if so, if the stones meet the proper values of >=1 and <=5
                        if($_SESSION['first_turn']){
                            if($input1 > 5 || $input2 > 5){
                                return FALSE;   
                            }
                        }
                        $previous_pile1 = $_SESSION['pile1'];
                        $previous_pile2 = $_SESSION['pile2'];
                        //if player takes away unequal amounts of stone then that's not allowed
                        if($input1 == 0 && $input2 == 0){
                            return FALSE;
                        }
                        if(($input1 >=1 && $input2 >=1) && ($input1 != $input2)){
                            return FALSE;
                        }
                        // Check and see if the new values are less than or equal to previous set values
                        if(($input1 > $previous_pile1) && ($input2 > $previous_pile2)){
                            return FALSE;
                        }
                        else{
                            //now check and see if the values don't lead to a negative value if subtracted from previous set values
                            if(($previous_pile1 - $input1 < 0) || ($previous_pile2 - $input2 < 0)){
                                return FALSE;
                            }
                            else{
                                return TRUE;
                            }
                        }
                    }
                    else{
                        return TRUE;
                    }
                }

                //function that returns the number of stones in each pile after optimal stones are taken away
                // return how many stones are taken away from pile in array format
                function computerTurn($first_pile, $second_pile){
                    // Because the computer is making its turn, make first_turn equal to false
                    $_SESSION['first_turn'] = FALSE;

                    if($first_pile == $second_pile){
                        echo "FIRST";
                        //most optimal to take all stones so player loses next turn
                        return array($first_pile, $second_pile);
                    }
                    if ($first_pile == 0){
                        echo "SECOND";

                        // first pile is empty, so just take all the stones out of the second pile
                        return array(0, $second_pile);
                    }
                    if ($second_pile == 0){
                        echo "THIRD";

                        // first pile is empty, so just take all the stones out of the second pile
                        return array($first_pile, 0);
                    }
                    // If one pile has only 1 stone and the difference between piles is greater than 1, then make the larger pile equal 2 for optimal pile structure
                    if(($first_pile == 1 || $second_pile == 1) && (abs($first_pile - $second_pile) > 1)){
                        echo "FOURTH";

                        if($first_pile < $second_pile){
                            return array(0, $second_pile - 2);
                        }
                        else{
                            return array($first_pile - 2, 0);
                        }
                    }
                    // If the difference is greater than 2 and each pile has more than equal to 2 stones, then get the larger pile and remove stones from until the diffence
                    // between the two piles is 2
                    if(($first_pile >= 2 && $second_pile >= 2) && (abs($first_pile - $second_pile) > 2)){
                        if($first_pile > $second_pile){
                            return array($first_pile - ($second_pile + 2), 0);
                        }
                        else{
                            return array(0, $second_pile - ($first_pile + 2));
                        }
                    }
                    // If either pile has only 2 stones, then just take away as many stones from the greater pile to get it in the optimal pile structure of 
                    // Pile 1 = o
                    // Pile 2 = o o
                    // or vice versa
                    if($first_pile == 2 && $second_pile >= 2){
                        return array(0, $second_pile - 1);
                    }
                    if($second_pile == 2 && $first_pile >= 2){
                        return array($first_pile -1 , 0);
                    }
                    // if they differ by one, take away enough stones so it's in this structure (or vice versa)
                    // Pile 1 = o
                    // Pile 2 = oo
                    if ((abs($first_pile - $second_pile) == 1) && ($first_pile >= 2 && $second_pile >= 2)){
                        if($first_pile > $second_pile){
                            return array($first_pile -2 , $second_pile - 1);
                        }
                        else{
                            return array($first_pile - 1, $second_pile -2);
                        }
                    } 
                    //If both piles have more than 3 stones, then take away until the smaller pile has only 3 stones. take same amount from pile 2
                    if($first_pile > 3 && $second_pile > 3 && abs($first_pile - $second_pile) >= 2){
                        if ($first_pile > $second_pile){
                            return array($second_pile - 3, $second_pile -3);
                        }
                        else{
                            return array($first_pile - 3, $first_pile -3);
                        }
                    }
                    //No optimal moves, so just take away one from either pile
                    $random_choice = rand(0,2);
                    switch($random_choice){
                        case 0:
                            return array(0,1);
                        case 1:
                            return array(1,0);
                        case 2:
                            return array(1,1);
                    }
                    
                }


                //Where everything goes on
                if(isset($_POST['pile1_input']) && isset($_POST['pile2_input']) && isset($_REQUEST['submit_newpiles'])){
                    global $pile1, $pile2, $current_turn;
                    // the values that the user inputted to take away
                    $new_pile1  = $_POST['pile1_input'];
                    $new_pile2 = $_POST['pile2_input'];
                    // check and see if user inputted valid values
                    if(isValid($new_pile1, $new_pile2)){
                        // Set the new values for the pile
                        $new_pile1 =  $_SESSION['pile1'] - $new_pile1;
                        $new_pile2 = $_SESSION['pile2'] - $new_pile2;
                        if($new_pile1 == 0 and $new_pile2 == 0){
                            //If both piles are 0 then show winner and redirect to home page
                            if($new_pile1 == 0 && $new_pile2 == 0){
                                $_SESSION['winner'] = "You've Won!";
                            ?>
                                <!-- Show the display -->
                                <style type="text/css">.display_winner{ display: flex;}</style>
                            <?php
                            }
                        }
                        else{
                            // Get the values after the computer's optimal calculation
                            $stones_taken = computerTurn($new_pile1, $new_pile2);

                            $_SESSION['taken1'] = $stones_taken[0];
                            $_SESSION['taken2'] = $stones_taken[1];

                            //Update the values on the page and in the session
                            $pile1 = $new_pile1 - $stones_taken[0];
                            $pile2 = $new_pile2 - $stones_taken[1];
                            $_SESSION['pile1'] = $pile1;
                            $_SESSION['pile2'] = $pile2;

                            //If both piles are 0 then show winner and redirect to home page
                            if($pile1 == 0 && $pile2 == 0){
                                $_SESSION['winner'] = "The Computer Won..";

                            ?>
                                <!-- Show the display -->
                                <style type="text/css">.display_winner{ display: flex;}</style>
                                
                            <?php
                            }

                            //Show opponent that it is computer's turn
                            $_SESSION['turn'] = "The Computer took away";
                            $current_turn = "The Computer took away";
                          
                        }
                    }
                    else{
                        $_SESSION['errormsg'] = "Please check all fields and make sure you've entered a valid number.";
                    }
                }
            ?>

            <!-- Video here -->
            <video autoplay muted loop class="river_video">
                <source src="media/river.mp4" type="video/mp4">
            </video>
            <div class="container">
                <!-- displays who won -->
                <div class="display_winner">
                    <div class="winner">
                        <?php echo $_SESSION['winner'];?>
                            <button onclick="playagain()">Play Again</button>
                    </div>
                </div>

                <div class="game_container">
                <div class="turn_display">
                        <?php 
                            $current_turn =  $_SESSION['turn'];
                            if($current_turn != "Your Turn"){
                                $comp_takes1 = $_SESSION['taken1'];
                                $comp_takes2 = $_SESSION['taken2'];
                                echo "<h2>$current_turn $comp_takes1 from Pile 1 and $comp_takes2 from Pile 2</h2>";
                            }
                            else{
                                echo "<h2>$current_turn</h2>";
                            }
                            
                            ?>
                </div>
                 <form class="both_piles" action="" method="post" name="newpile">
                        <!-- displaying first pile -->
                        <div class="pile1_box">
                            <h1>Pile 1: <?php echo $pile1?> Stones</h1>
                            <div class="pile1">
                                <?php 
                                    for ($i = 0; $i < $pile1; $i++) {
                                        echo "<img class='single_stone' src='media/stone.png'>";
                                    }
                                ?>
                            </div>
                        </div>

                        <!-- displaying the second pile -->
                        <div class="pile2_box">
                            <h1>Pile 2: <?php echo $pile2?> Stones </h1>
                            <div class="pile2">
                                <?php 
                                    for ($i = 0; $i < $pile2; $i++) {
                                        echo "<img class='single_stone' src='media/stone.png'>";
                                    }
                                ?>
                            </div>
                        </div>
                            <div class="input_container">
                                <h1>How many stones would you like to take away from each pile?</h1>
                                <input name="pile1_input" value="Pile 1" onfocus="if (this.value=='Pile 1') this.value='';"/>
                                <input name="pile2_input" value="Pile 2" onfocus="if (this.value=='Pile 2') this.value='';" />
                            </div>
                            <button class="newpile_submit" name="submit_newpiles" >End Turn</button>
                            <h3><?php echo $_SESSION['errormsg'];?></h3>

                        </form>
                </div>
            </div>
    </body>
</html>