<?php    // Draw.php

class Draw {

protected $color_line;
protected $cell_size;
protected $midpt;

public $x1;
public $y1;
public $x2;
public $y2;

//---------------------
public function __construct ($width, $height, $color_background, $color_line, $cell_size, $midpt) {

    $this->cell_size = $cell_size;
    $this->midpt = $midpt;

    ?>
        <div style='margin-left:40px;margin-top:10px' id='maze'></div>
        <div style='margin-left:40px;margin-top:10px' id='winners'></div>

        <script type="text/javascript">

        if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){ //test for MSIE x.x;
            document.write("Microsoft Internet Explorer users:  If you don't see the maze, try Chrome, Firefox, Safari, or some other browser");
        }

            var svgid = d3.select ('#maze');
            var svg = svgid.append("svg");

            svg.attr ("x", 0)
               .attr ("y", 0)
               .attr ("width", <?echo $width?>)
               .attr ("height", <?echo $height?>);

            var rects = svg.append ('rect');

            rects.attr ("x", 0)
                 .attr ("y", 0)
                 .attr ("width", <?echo $width?>)
                 .attr ("height", <?echo $height?>)
                 .attr ("fill", 'grey');
    <?

    $this->color_line = $color_line;

} // end public function __construct ()



//---------------------
public function drawPath (&$path) {

        // build path attr string
    $moveto = TRUE;
    foreach ($path as $coor) {

        $cx = $this->px ($coor [0]);
        $cy = $this->px ($coor [1]);

        if ($moveto) {

            $path_string = "'M$cx $cy ";
            $moveto = FALSE;

        } else {

            $path_string .= "L$cx $cy ";

        } // end if ($moveto)

    } // end foreach ($path as $coor)
    $path_string .= "'";

    ?>
        var mpath = svg.append ('path');
            mpath.attr ('d', <?echo $path_string?>)
                 .attr ('fill', 'none')
                 .attr ('stroke', 'blue')
                 ;

    <?


} // end public function drawPath ()

//---------------------
public function px ($coor) {

    $res = $coor * $this->cell_size + $this->midpt;
    return $res;

} // end public function px ()


//---------------------
public function out ($stmt) {

    echo $stmt;
    echo "\n";


} // end public function out ()


}
