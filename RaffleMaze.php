<?php    // RaffleMaze.php

require_once 'Draw.php';
require_once 'MaxPaths.php';

class RaffleMaze {

protected $x_dim;
protected $y_dim;

protected $maze;
protected $svg;

protected $max_dist;
protected $max_x;
protected $max_y;

protected $branch;  
protected $pathfromstart;

protected $names;
protected $colors;
protected $winners;

protected $numentrants;
protected $numlosers;

protected $pathstoentrants;
protected $numpathstoentrants;

protected $MP;

//---------------------
public function __construct ($names, $numwin, $colors, $textcolors, $x_dim, $y_dim, $cell_size) {

    mt_srand (time ());

    $this->names = $names;
    $this->numwin = $numwin;

    $this->colors = $colors;
    $this->textcolors = $textcolors;

    $this->numentrants = sizeof ($names);
    $this->numlosers = $this->numentrants - $numwin;

    $this->pathstoentrants = array ();
    $this->numpathstoentrants = 0;

    $this->MP = new MaxPaths ();

    $md = $cell_size % 2;
    
    if ($md == 0) {
        $cell_size += 1;
    }

    $midpt = intval ($cell_size / 2) + 1;
    
    $width = $x_dim * $cell_size;
    $height = $y_dim * $cell_size;
    
    $this->svg = new Draw ($width, $height, 'grey', 'blue', $cell_size, $midpt);
    
    for ($j = 0; $j < $y_dim; $j++) {
    
        $row = array ();
    
        for ($i = 0; $i < $x_dim; $i++) {
    
            $row [] = FALSE;
    
        } // end for ($i = 0; $i < $x_dim; $i++)
        
        $this->maze [] = $row;
    
    } // end for ($j = 0; $j < $y_dim; $j++)
    
    $startx = mt_rand (0, $x_dim - 1);
    $starty = mt_rand (0, $y_dim - 1);

    $this->x_dim = $x_dim;
    $this->y_dim = $y_dim;

    $this->pickNextNeighbor ($startx, $starty, $destx, $desty);
    
    $this->markVisited ($startx, $starty);
    
    $this->max_dist = 0;
    $this->max_x = 0;
    $this->max_y = 0;

    $this->pathfromstart = array (array ($startx, $starty));

    $this->visit (TRUE, 1, $startx, $starty, $destx, $desty);
    
    $this->genBoxesFuse ($startx, $starty, $cell_size, $midpt);

} // end function __construct ()

//---------------------
protected function genBoxesFuse ($startx, $starty, $cell_size, $midpt) {

    $maxpaths = $this->MP->getMaxData ($this->numentrants);

    ?>

    boxes ();

    function boxes () {
        var cstart = svg.append ('circle');
        cstart.attr ('cx', <?echo $startx * $cell_size + $midpt?>)
              .attr ('cy', <?echo $starty * $cell_size + $midpt?>)
              .attr ('r', <?echo $cell_size / 2 + 2?>)
              .attr ("fill", 'red');


        <?

        for ($p = 0; $p < sizeof ($maxpaths); $p++) {

            $path =& $maxpaths [$p];
            $dest = end ($path);

            $lastx = $dest [0];
            $lasty = $dest [1];

            $color = "'" . $this->colors [$p] . "'";

            ?>
            var rstop = svg.append ('rect');
            rstop.attr ("x", <?echo $lastx * $cell_size?>)
                 .attr ("y", <?echo $lasty * $cell_size?>)
                 .attr ("width", <?echo $cell_size?>)
                 .attr ("height", <?echo $cell_size?>)
                 .attr ("fill", <?echo $color?>);
            <?
        
        } // end for ($p = 0; $p < sizeof ($maxpaths); $p++)
        ?>

    }

    function lightfuse () {

        String.prototype.repeat = function(times) {
            return (new Array(times + 1)).join(this);
        }
    
        segmentsa = [
        <?

        $losers = array ();
        $this->winners = array ();

        for ($m = 0; $m < $this->numentrants; $m++) {

            $losers [] = $m;

        } // end for ($m = 0; $m < $this->numentrants; $m++)
        
        $lastpath = sizeof ($maxpaths) - 1;
        for ($n = 0; $n < $this->numwin; $n++) {

            $w = mt_rand (0, $lastpath);

            $maxpaths [$w] = $maxpaths [$lastpath];
            $this->winners [] = $losers [$w];

            $losers [$w] = $losers [$lastpath];

            $lastpath--;

        } // end for ($n = 0; $n < $numwin; $n++)

        sort ($this->winners);
        
        $numpaths = $lastpath + 1;
        for ($p = 0; $p < $numpaths; $p++) {

            $path =& $maxpaths [$p];
            echo '[';
            $numpts = sizeof ($path);
            for ($q = 0; $q < $numpts; $q++) {

                $coor = $path [$q];
                $x = $coor [0] * $cell_size + $midpt;
                $y = $coor [1] * $cell_size + $midpt;

                echo "[$x,$y]";
                if ($q <= $numpts - 2) {

                    echo ',';

                } // end if ($q <= $numpts - 2)
                

            } // end for ($q = 0; $q < $numpts; $q++)
            
            echo ']';
            if ($p <= $numpaths - 2) {

                echo ',';

            } // end if ($p < $numpaths - 2)
            
        } // end for ($p = 0; $p < sizeof ($maxpaths); $p++)
        ?>
    ];

    for (var z = 0; z < segmentsa.length; z++) {
        segments = segmentsa[z];
        line = "M"+segments[0][0] + " " + segments[0][1];
        
        new_line = line + (" L" + segments[0][0] + " " + segments[0][1]).repeat(segments.length);

         var mpath = svg.append ('path').attr ('d',new_line )
                     .attr ('fill', 'none')
                     .attr ('stroke', 'red')
                     ;
        
        for (i=0; i<segments.length; i++) {
            new_segment = " " + "L"+segments[i][0] + " " + segments[i][1]
            new_line = line + new_segment.repeat(segments.length-i)
            mpath.transition().attr('d',new_line).duration(0)
            .delay(i*30)
            ;
            line = line + new_segment;
        
        }
    }

    setTimeout(sw, 7000);

    }

    function sw() {

    document.getElementById('winners').innerHTML = "<input style='margin-left:25px;margin-top:10px;border-radius:12px;background-color:pink;font-size:80%' type='submit' value='Show Winners' onclick='showwinners()'/>";
        
    }

    function showwinners () {

        <?

        for ($p = 0; $p < $numpaths; $p++) {

            $path =& $maxpaths [$p];
            $dest = end ($path);

            $lastx = $dest [0];
            $lasty = $dest [1];

            ?>
            var rstop = svg.append ('rect');
            rstop.attr ("x", <?echo $lastx * $cell_size?>)
                 .attr ("y", <?echo $lasty * $cell_size?>)
                 .attr ("width", <?echo $cell_size?>)
                 .attr ("height", <?echo $cell_size?>)
                 .attr ("fill", 'black');
            <?
        
        } // end for ($p = 0; $p < sizeof ($maxpaths); $p++)
        ?>
    document.getElementById('winnerlist').style.visibility='visible';

    }
        

    </script>

     <?
    $this->showWinners ();

    $this->closeHtmlTags ();

} // end function genBoxesFuse ()


//---------------------
protected function markVisited ($curx, $cury) {

    $row =& $this->maze [$cury];
    $row [$curx] = TRUE;

    return;

} // end function markVisited ()


//---------------------
protected function visit ($newbranch, $dist, $curx, $cury, $destx, $desty) {

    $this->pathfromstart [] = array ($destx, $desty);

    if ($dist > $this->max_dist) {

        $this->max_dist = $dist;
        $this->max_x = $destx;
        $this->max_y = $desty;

    } // end if ($dist > $this->max_dist)
    
    if ($newbranch) {

        $this->branch = array (array ($curx, $cury), array ($destx, $desty));
        $newbranch = FALSE;

    } else {

        $this->branch [] = array ($destx, $desty);

    } // end if ($newbranch)

    $curx = $destx;
    $cury = $desty;

    $this->markVisited ($curx, $cury);

    $visiting = TRUE;
    $firstpass = TRUE;

    while ($visiting) {
        
        $this->pickNextNeighbor ($curx, $cury, $destx, $desty);

        if ($destx === -1) {

            if ($firstpass) {

                $this->MP->insert ($dist, $this->pathfromstart);
                $this->svg->drawPath ($this->branch);

            } // end if ($firstpass)

            array_pop ($this->pathfromstart);

            return;

        } // end if ($destx === -1)

        $firstpass = FALSE;

        $this->visit ($newbranch, $dist + 1, $curx, $cury, $destx, $desty);
        
        $newbranch = TRUE;

    } // end while ($visiting)
    


} // end function explore ()



//---------------------
protected function pickNextNeighbor ($curx, $cury, &$destx, &$desty) {

    $destx = -1;
    $desty = -1;

    $neighbors = $this->unvisitedNeighbors ($curx, $cury);

    if ($neighbors === null) {

        return;

    } // end if ($neighbors === null)
    
    $choice = mt_rand (0, sizeof ($neighbors) - 1);

    $neighbor = $neighbors [$choice];

    $destx = $neighbor [0];
    $desty = $neighbor [1];
    
    return;

} // end function pickNextNeighbor ()



//---------------------
protected function unvisitedNeighbors ($curx, $cury) {

        // counter-clockwise from the 3 o'clock position
    $dax = array (1, 0, -1, 0);
    $day = array (0, -1, 0, 1);

    $unvisited_neighbors = null;

    for ($i = 0; $i < 4; $i++) {

        $dx = $dax [$i];
        $dy = $day [$i];

        $nx = $curx + $dx;
        $ny = $cury + $dy;

        if ($nx > -1 && $nx < $this->x_dim && $ny > -1 && $ny < $this->y_dim) {  // skip unless within maze boundary

            $row =& $this->maze [$ny];

            if (! $row [$nx]) {  // check if unvisited

                $unvisited_neighbors [] = array ($nx, $ny);

            } // end if ($row [$nx])
            

        } // end if ($nx > -1 && $nx < $this->x_dim && $ny > -1 && $ny < $this->y_dim)
        
        
    } // end for ($i = 0; $i < 4; $i++)

    return $unvisited_neighbors;
    
} // end function unvisitedNeighbors ()


//---------------------
protected function closeHtmlTags () {

    print_r ("</body>\n</html>");

} // end function closeHtmlTags ()


//---------------------
protected function showWinners  () {

    ?>
        <div id='winnerlist' style="display:inline-block;background-color:#D1E8D1;padding:8px;padding-right:40px;border:1px solid blue;border-radius:8px;margin-top:40px;margin-left:40px;margin-right:40px">
        <span style="margin-left:20px;font-size:150%">Winners</span>
        <br/>
        <?
            $numwinners = sizeof ($this->winners);

            for ($i = 0; $i < $numwinners; $i++) {

                $wnr = $this->winners [$i];

                $clr = $this->colors [$wnr];
                $tclr = $this->textcolors [$wnr];

                $name = $this->names [$wnr];

                $lbl = "<input style='font-weight:bold;color:$tclr;background-color:$clr;border-radius:4px' type='submit' value='$name' />";

                echo $lbl;

            } // end for ($i = 0; $i < $numnames; $i++)

        ?>
        </div>
    <script type='text/javascript'>
    document.getElementById('winnerlist').style.visibility='hidden';
    </script>
    <?


} // end function showWinners  ()

} // end class RaffleMaze
