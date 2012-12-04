<?php    // MaxPaths.php

Class MaxPaths {  // implemented as a binary tree

protected $oltree;

protected $maxdata;

protected $maxnum;
protected $maxfound;

//---------------------
public function __construct () {

    $this->oltree = NULL;

} // end function __construct ()



//---------------------
public function insert ($key, $data) {

    $subtree =& $this->findNode ($key);

    if ($subtree == NULL) {

        $subtree = $this->newNode ($key, $data);

    } else {

        $subtree ['data'] [] = $data;

    } // end if ($subtree == NULL)

    return;

} // end function insert ()



//---------------------
public function getMaxData ($maxnum) {

    $this->maxnum = $maxnum;
    $this->maxfound = 0;

    $this->maxdata = array ();

    $this->doGetMaxData ($this->oltree);

    return $this->maxdata;

} // end function getMaxData ()



//---------------------
protected function doGetMaxData  (&$subtree) {

    if ($subtree == NULL) {

        return;

    } // end if ($this->maxfound == $this->maxnum)
    
    $this->doGetMaxData ($subtree ['gt']);

    if ($this->maxfound == $this->maxnum) {

        return;

    } // end if ($this->maxfound == $this->maxnum)

    for ($i = 0; $i < sizeof ($subtree ['data']); $i++) {

        $this->maxdata [] =& $subtree ['data'] [$i];
        $this->maxfound++;

        if ($this->maxfound == $this->maxnum) {

            return;

        } // end if ($this->maxfound == $this->maxnum)
        
    } // end for ($i = 0; $i < sizeof ($subtree ['data']); $i++)
    
    $this->doGetMaxData ($subtree ['lt']);

} // end function doGetMaxData  ()

//---------------------
protected function newNode ($key, $data) {

    $node = array ('key' => $key, 'lt' => NULL, 'gt' => NULL, 'data' => array ($data));

    return $node;

} // end function newNode ()



//---------------------
protected function &findNode ($key) {

    return $this->doFindNode ($this->oltree, $key);

} // end function findNode ()



//---------------------
protected function &doFindNode (&$subtree, $key) {

    if ($subtree == NULL   or   ($sk = $subtree ['key']) == $key) {

        return $subtree;

    } // end if ($subtree ['key'] == $key)

    $newsubtree =& $subtree [$this->compare ($key, $sk)];

    return $this->doFindNode ($newsubtree, $key);
    
} // end function doFindNode ()

//---------------------
protected function compare ($x, $y) {

    if ($x < $y) {

        $res = 'lt';

    } elseif ($x > $y) {

        $res = 'gt';

    } else {

        $res = 'data';

    } // end if ($x < $y)
    
    return $res;

} // end function compare ()


}  // end Class MaxPaths

