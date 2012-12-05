<?php    // Raffle.php

require_once 'RandHexColor.php';
require_once 'RaffleMaze.php';

class Raffle {

protected $names;
protected $numwin;
protected $colors;

//---------------------
public function __construct ($names, $numwin) {

    $this->names = $names;
    $this->numwin = $numwin;

    $this->startRaffle ();

} // end function __construct ()


//---------------------
public function startRaffle () {

    $this->header (); 
    $this->listEntries ();    
    $this->showStart ();    
    $this->genMaze ();
    $this->footer ();

} // end function startRaffle ()


//---------------------
public function header () {

    ?>
        <html>
        <head>
            <script type="text/javascript" src="d3.v2.min.js"></script>
        </head>
        <body style='background-color=#f0f0f0'>
    <?

} // end function header ()


//---------------------
public function footer () {

    ?>
        <noscript>Javascript must be enabled to see the maze <br />but your browser currently does not have Javascript enabled.  <br /><br />To enable Javascript:<br />  Firefox: from Edit/Preferences/Content, check "Enable Javascript"
        <br /><br />IE:  From Tools/Internet Options/Security/Custom Level, select "Enable" under Scripting/Active Scripting<br /><br />Chrome:  Settings(wrench icon)/Options/Under the Hood/Content Settings/Javascript, Select "Allow all sites to run Javascript" or for this site only, Select "Manage Exceptions" and enter "tgregoneil.com" as a new Pattern.
        <br /><br />Safari:  Settings(wheel icon)/Preferences/Security/, Select Enable JavaScript
        </noscript>
        </body>
        </html>
    <?

} // end function footer ()


//---------------------
public function listEntries () {

    ?>
        <div style="display:inline-block;background-color:#D1E8D1;padding:8px;padding-right:40px;border:1px solid blue;border-radius:8px;margin:0px;margin-left:40px;margin-right:40px">
        <span style="margin-left:20px;font-size:150%">Entries</span>
        <br/>
        <?
            $numnames = sizeof ($this->names);
            $res = $numnames <= 64 ? 64 : 32;

            $hexcolors = new RandHexColor ($res);

            $this->colors = $hexcolors->genRndColorSetUniq ($numnames);
            $this->textcolors = $hexcolors->getTextColors ();

            for ($i = 0; $i < $numnames; $i++) {

                $clr = $this->colors [$i];
                $tclr = $this->textcolors [$i];

                $name = $this->names [$i];

                $lbl = "<input style='font-weight:bold;background-color:$clr;color:$tclr;border-radius:4px' type='submit' value='$name' />";

                echo $lbl;

            } // end for ($i = 0; $i < $numnames; $i++)
            
        ?>
        </div>
    <?

} // end function listEntries ()


//---------------------
public function showStart () {

    echo "</br>";

    $start = "<input style='margin-left:100px;margin-top:10px;border-radius:12px;background-color:#ff4040;font-size:150%' type='submit' value='Light Fuse' onclick='lightfuse()'/>";
    echo $start;

} // end function showStart ()



//---------------------
public function genMaze () {

    $maze = new RaffleMaze ($this->names, $this->numwin, $this->colors, $this->textcolors, 20, 20, 12);

} // end function genMaze ()

}
