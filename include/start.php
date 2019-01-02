<html>
        <head>
        </head>
            <title>Start Game</title>
            <link href="" type="text/css" rel="stylesheet" />

        <body>

            <h1>This the game page </h1>
            <?php 
                session_start();

                $pile1= $_SESSION['pile1'];
                $pile2= $_SESSION['pile2'];


                //Function 

                //check to see if user inputs are integers and possible given current number of stones
                function isValid($input1, $input2){
                    // check to see if the inputs are numbers
                    if(is_numeric($input1) && is_numeric($input2) && isset($_REQUEST['submit_newpiles'])){
                        // echo "THEY NUMBERIC BOYS";
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
                            if(($previous_pile1 - $input1 < 0) or ($previous_pile2 - $input2 < 0)){
                                return FALSE;
                            }
                            else{
                                return TRUE;

                            }
                        }
                    }

                    else{
                        return FALSE;
                    }
                }



                //function that returns the number of stones in each pile after optimal stones are taken away
                // return how many stones are taken away from pile
                function computerTurn($first_pile, $second_pile){
                    //arrays that describe how many stones from each pile the computer took away; there will be two elements in each that correspond to the number of stones in each pile, respectively

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
                    // If either pile has only 2 stones, then just take away as many stones from the greater pile to get it in the optimal pile structure of 
                    // Pile 1 = o
                    // Pile 2 = o o
                    // or vice versa
                    if($first_pile == 2 && $second_pile >= 2){
                        echo "FIFTH";

                        return array(0, $second_pile - 1);
                    }
                    if($second_pile == 2 && $first_pile >= 2){
                        echo "SIXTH";

                        return array($first_pile -1 , 0);
                    }
                    // if they differ by one, take away enough stones so it's in this structure (or vice versa)
                    // Pile 1 = o
                    // Pile 2 = oo
                    if ((abs($first_pile - $second_pile) == 1) && ($first_pile >= 2 && $second_pile >= 2)){
                        echo "SEVENTH";

                       
                        if($first_pile > $second_pile){
                            return array($first_pile -2 , $second_pile - 1);
                        }
                        else{
                            return array($first_pile - 1, $second_pile -2);
                        }
                    } 
                    //If both piles have more than 3 stones, then take away until the smaller pile has only 3 stones. take same amount from pile 2
                    if($first_pile > 3 && $second_pile > 3 && abs($first_pile - $second_pile) >= 2){
                        echo "EIGHTH";

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
                    global $pile1, $pile2;
                    // the values that the user inputted to take away
                    $new_pile1  = $_POST['pile1_input'];
                    $new_pile2 = $_POST['pile2_input'];
                    // check and see if user inputted valid values
                    if(isValid($new_pile1, $new_pile2)){
                        // Set the new values for the pile
                        $new_pile1 =  $_SESSION['pile1'] - $new_pile1;
                        $new_pile2 = $_SESSION['pile2'] - $new_pile2;

                        if($new_pile1 == 0 and $new_pile2 == 0){
                            echo "You've Won!";
                        }
                        else{
                            // Get the values after the computer's optimal calculation
                            $stones_taken = computerTurn($new_pile1, $new_pile2);
                            echo "The computer took away **";
                            echo $stones_taken[0];
                            echo "**from Pile 1 and **";
                            echo $stones_taken[1];
                            echo "** from Pile 2";
                            echo "<br>";

                            //Update the values on the page and in the session
                            $pile1 = $new_pile1 - $stones_taken[0];
                            $pile2 = $new_pile2 - $stones_taken[1];
                            $_SESSION['pile1'] = $pile1;
                            $_SESSION['pile2'] = $pile2;

                            // if after the computer computers the stones and there's no stones left, then the computer won
                            if($pile1 == 0 && $pile2 == 0){
                                echo "The Computer Won!";
                            }
                        }
                    }
                    else{
                        echo "nah you fked up";
                    }
                }
            ?>

            <div class="pile1">
                <p>The first pile is now: </p>
               <?php echo $pile1 ?>
            </div>
            <div class="pile2">
                <p>The second pile is now: </p>

                <?php echo $pile2?>
            </div>

            <form action="" method="post" name="newpile">
                <input name="pile1_input" value="Pile 1" onfocus="if (this.value=='Pile 1') this.value='';"/>
                <input name="pile2_input" value="Pile 2" onfocus="if (this.value=='Pile 2') this.value='';" />
                <button name="submit_newpiles" >SUBMIT</button>
            </form>
    </body>
</html>