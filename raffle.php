<?php    // raffle.php

require_once 'Raffle.php';

if ($_POST) {

    if (getNamesNumWinners ($names, $numwin)) {

        doRaffle ($names, $numwin);

    } else {

        $numnames = sizeof ($names);
        $numwin = mt_rand (1, sizeof ($names) - 1);

        $errmsg = "<p style='color:red'>"
        . "Number of winners must be greater than 0 and less than number of names ($numnames).  Let's try '2', for example."
        . "</p>";
        echo $errmsg;

        showNamesForm ($names, "'2'");

    } // end if (getNamesNumWinners ($names, $numwin);)
    
} else {

    $names = array ('Calvin', 'Hobbes', 'Susie Derkins', 'Mr. Bun', 'Mom', 'Dad', 'Miss Wormwood');
    shuffle ($names);

    $numwin = mt_rand (1, sizeof ($names) - 1);
    showNamesForm ($names, "'$numwin'");

} // end if ($_POST)



//---------------------
function showNamesForm ($names, $numwin) {

    $namestr = "";
    foreach ($names as $name) {

        $namestr .= $name . "\n";

    } // end foreach ($names as $name)

    $inp = "<input type='text' name='numwin' style='width:5em'";
    $inp .= " value=" . $numwin;
    
    if (strlen($namestr) > 0) {

        $inp .= "autofocus";

    } // end if (strlen($namestr) > 0)
    
    ?>
    <form method='post' action='raffle.php'>
        Free stuff is good.  Enter names:
        <br/>
        <textarea name='entries' cols='40' rows='20' <?if(strlen($namestr) == 0) {echo "autofocus";}?>><?echo $namestr?></textarea>
        <br/>
        Number of winners:
        <?echo $inp?>
        <br/>
        <input type='submit' value='Start'/>
    </form>
    <?

} // end public function getNames ()


//---------------------
function getNamesNumWinners  (&$names, &$numwin) {

    $names = array ();

    $numwin = $_POST ['numwin'];
    $entries = $_POST ['entries'];

    $entries = ltrim ($entries);
    $entries = rtrim ($entries);

    $list = preg_split ('/\r?\n/', $entries);

    foreach ($list as $l ) {

        if (strlen ($l)) {

            $names [] = $l;

        } // end if (strlen ($l))
        
    } // end foreach ($list as $l )

    $numok = FALSE;

    if ($numwin > 0 && $numwin < sizeof ($names)) {

        $numok = TRUE;

    } // end if ($numwin > 0 && $numwin < sizeof ($names))
    
    return $numok;
    
} // end public function getNamesNumWinners  ()


//---------------------
function doRaffle ($names, $numwin) {

    new Raffle ($names, $numwin);
    
} // end public function doRaffle ()

