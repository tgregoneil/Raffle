<?php    // RandHexColor.php

Class RandHexColor {

protected $resolution;
protected $numintervals;
protected $colors_generated;
protected $maxcolors;

//---------------------
public function __construct ($resolution) {

    $this->resolution = $resolution;
    $this->numintervals = intval (256 / $resolution);

    $this->maxcolors = $this->numintervals * $this->numintervals * $this->numintervals;
    $this->colors_generated = array ();

} // end public function __construct ()


//---------------------
public function genRndColorSetUniq ($numcolors) {

    $this->colors_generated = array ();

    if ($numcolors <= $this->maxcolors) {

        for ($i = 0; $i < $numcolors; $i++) {

            $this->genRndColorUniq ();

        } // end for ($i = 0; $i < $numcolors; $i++)

    } // end if ($numcolors <= $this->maxcolors)
    
    return array_keys ($this->colors_generated);

} // end public function genRndColorSetUniq  ()

//---------------------
public function genRndColorUniq () {

    while (1) {

        $c = $this->genRndColor ();
        if (! array_key_exists ($c, $this->colors_generated)) {

            $this->colors_generated [$c] = 1;
            break;

        } // end if (! array_key_exists ($c, $this->colors_generated))

    } // end while (1)

    return $c;
    

} // end public function genRndColorUniq ()

//---------------------
public function genRndColor () {

    $r = $this->genColorComponent (); 
    $g = $this->genColorComponent (); 
    $b = $this->genColorComponent (); 

    return '#'.$r.$g.$b;
    
} // end public function genRndColor ()


//---------------------
protected function genColorComponent () {

    
        // mt_rand res - 255, incrs of res
    $v = mt_rand (1,$this->numintervals) * $this->resolution - 1;  

    $hexval = str_pad (dechex ($v), 2, '0', STR_PAD_LEFT);
    return $hexval;

}  // end protected function genColorComponent 

} // end Class RandHexColor {


//---------------------

