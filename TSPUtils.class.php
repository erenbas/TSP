<?php

/**
 * Description of TSPUtils
 *
 * @author eren
 */
class TSPUtils {
		
    private $nodes = array();
    private $sides = array();

    public function __construct($nodes){
        
        $this->nodes = array_unique($nodes);
    }

    public function get_sides(){
        
        return $this->sides;
    }

    public function populate_sides(){

        $nodes2 = $this->nodes;
        $i = 0;

        foreach($this->nodes as $key1 => $value1){

            unset($nodes2[$i]);
            $i++;

            foreach($nodes2 as $key2 => $value2){

                $this->sides[$value1.$value2] = rand(1,20);		
            }
        }
    }
}

