<?php

/**
 * Description of TSP
 *
 * @author eren
 */
class TSP {
	
    private $nodes = array();
    private $startPoint;
    private $groupedSides = array();

    public $numberOfTriangles;
    public $route = array();
    public $travelledPath = array();
    public $totalDistance = 0;

    public function __construct($nodes){
            
        $this->nodes = array_unique($nodes);
    }

    public function set_distances($distances){
            
        $this->distances = $distances;
    }

    public function set_starting_point($node){
            
        $this->startPoint = $node;
    }

    public function display_route(){
            
        echo "The shortest route: \n";
            
        print_r($this->route);	
    }

    public function display_journey(){
            
        echo "The journey: \n";
            
        print_r($this->travelledPath);
    }

    public function display_total_distance(){
            
        echo "The total distance: ".$this->totalDistance;
    }
	
    #---> discovers the shortest path of number of given nodes
    public function get_shortest_path(){
				
        $triangles = $this->generate_triangles();

        $sorted = $this->evaluate_triangles($triangles);

        $size=sizeof($this->nodes);

        #---> first step which the starting point
        $this->route[] = $this->startPoint;
		
            for($i=1;$i<=$size;$i++){

                if( $i == 1 ){

                    foreach($sorted as $name => $value){
                        if( strpos($name,$this->startPoint) !== false ){

                            $side = $this->get_right_side($this->startPoint,$this->groupedSides[$name]);

                            unset($sorted[$name]);
                            $currentNode = $this->get_current_node($this->startPoint,$side);

                            #---> store the first step
                            $this->route[] = $currentNode;

                            #---> store the path and distance
                            $this->travelledPath[$side] = $this->groupedSides[$name][$side];

                            #---> sum the distance travelled
                            $this->totalDistance += $this->groupedSides[$name][$side];

                            break;
                        }
                    }

                } else if ($i > 1 && $i < $size) {
				
                    $currentNode2 = $currentNode;

                    foreach($sorted as $name => $value){

                        if( strpos($name,$this->startPoint) === false && strpos($name,$currentNode2) !== false ){

                            $side = $this->get_right_side($currentNode2,$this->groupedSides[$name]);

                            $currentNode = $this->get_current_node($currentNode2,$side);

                            if ( array_search($currentNode,$this->route) === false ){

                                $currentNode2 = $currentNode;

                                unset($sorted[$name]);

                                #---> store the other intermediate steps
                                $this->route[] = $currentNode;

                                #---> store the path and distance
                                $this->travelledPath[$side] = $this->groupedSides[$name][$side];

                                #---> sum the distance travelled
                                $this->totalDistance += $this->groupedSides[$name][$side];

                                break;
                            }
                        }
                    }
				
                } else if( $i == $size){

                    $currentNode3 = $currentNode2;

                    foreach($sorted as $name => $value){

                        if( strpos($name,$this->startPoint) !== false && strpos($name,$currentNode3) !== false ){

                            $side = $this->get_right_side($currentNode3,$this->groupedSides[$name]);

                            $currentNode = $this->get_current_node($currentNode3,$side);

                            if ( array_search($currentNode,$this->route) === false ){

                                $currentNode3 = $currentNode;

                                #---> store the step right before returning the starting point
                                $this->route[] = $currentNode;

                                #---> store the path and distance
                                $this->travelledPath[$side] = $this->groupedSides[$name][$side];

                                #---> sum the distance travelled
                                $this->totalDistance += $this->groupedSides[$name][$side];

                                break;

                            }
                        }						
                    }			
                }			
            }
		
        #---> store the last step which is the starting point
        $this->route[] = $this->startPoint;

        #---> store the path and distance
        $this->travelledPath[$this->startPoint.$currentNode3] = $this->distances[$this->startPoint.$currentNode3];

        #---> sum the distance travelled
        $this->totalDistance += $this->distances[$this->startPoint.$currentNode3];
    }

    #---> Extracts current node from the direction
    private function get_current_node($startingPoint,$direction){

        if($startingPoint == $direction[0]) {
            return $direction[1];
        } else {
            return $direction[0];
        }

    }
	
    #---> builds unique triangles from nodes
    private function generate_triangles() {

        $nodes1 = $this->nodes;
        $nodes2 = $this->nodes;
        $nodes3 = $this->nodes;

        foreach($nodes1 as $key1 => $node1){

            unset($nodes2[$key1]);

            foreach($nodes2 as $key2 => $node2){

                unset($nodes3[$key1]);

                foreach($nodes3 as $key3 => $node3){

                    if($node3 != $node2 && $node3 != $node1){

                        $tri=array($node1,$node2,$node3); 

                        sort($tri);
                        $array[$tri[0].$tri[1].$tri[2]] = $tri;
                    }
                }
            }	
        }

        return $array;
    }
	
    #---> sorts triangle combinations (unique triangles) by multiplying the sides
    private function evaluate_triangles($triangles){

        $array = array();

        foreach($triangles as $key => $triangle){

            $array[$key] = $this->distances[$triangle[0].$triangle[1]] * $this->distances[$triangle[1].$triangle[2]] * $this->distances[$triangle[0].$triangle[2]];

            $sides = array($triangle[0].$triangle[1]=>$this->distances[$triangle[0].$triangle[1]],
                                        $triangle[1].$triangle[2]=>$this->distances[$triangle[1].$triangle[2]],
                                            $triangle[0].$triangle[2]=>$this->distances[$triangle[0].$triangle[2]]);
            asort($sides);

            $this->groupedSides[$key] = $sides;

        }

        asort($array);
        return $array;
    }
	
    #---> calculates number of unique triagles from number of nodes based on combination formula
    private function get_number_of_triagles(){
        
        $nodeSize = sizeof($this->nodes);
        return $this->factorial($nodeSize)/($this->factorial(3)*$this->factorial(($nodeSize-3)));
    }

    #---> recursive factorial math function to calculate factorial
    private function factorial($number) { 
        
        if ($number < 2) { 
            return 1; 
        } else { 
            return ($number * $this->factorial($number-1)); 
        } 
    }

    private function get_right_side($currentNode,$sides){
        
        foreach($sides as $side => $distance){
            if( strpos($side,$currentNode) !== false ){
                return $side;
            }
        }
    }
	
	
}

/*
$TSP = new TSP(array('A','B','C','D','E'));
$TSP->set_distances(array('AB'=>3,'AC'=>6,'AD'=>9,'AE'=>5,'BC'=>7,'BD'=>8,'BE'=>10,'CD'=>9,'CE'=>6,'DE'=>4));
$TSP->set_starting_point('A');
$TSP->get_shortest_path();
$TSP->display_route();
$TSP->display_journey();
$TSP->display_total_distance();

$TSP = new TSP(array('A','B','C','D','E','F'));
$TSP->set_distances(array('AB'=>3,'AC'=>6,'AD'=>9,'AE'=>5,'AF'=>6,'BC'=>7,'BD'=>8,'BE'=>10,'BF'=>4,'CD'=>9,'CE'=>6,'CF'=>10,'DE'=>4,'DF'=>12,'EF'=>5));

$TSP = new TSP(array('A','B','C','D','E','F','G','H'));
$TSP->set_distances(array('AB'=>3,'AC'=>6,'AD'=>9,'AE'=>5,'AF'=>6,'AG'=>8,'AH'=>11,
						   'BC'=>7,'BD'=>8,'BE'=>10,'BF'=>4,'BG'=>7,'BH'=>5,
						   'CD'=>9,'CE'=>6,'CF'=>10,'CG'=>7,'CH'=>12,
						   'DE'=>4,'DF'=>12,'DG'=>3,'DH'=>7,
						   'EF'=>5,'EG'=>8,'EH'=>9,
						   'FG'=>8,'FH'=>4,
						   'GH'=>10));
						   
$TSP = new TSP(array('A','B','C','D','E','F','G','H','K','L','M','N'));
$TSP->set_distances(array('AB'=>3,'AC'=>6,'AD'=>9,'AE'=>5,'AF'=>6,'AG'=>8,'AH'=>11,'AK'=>7,'AL'=>6,'AM'=>13,'AN'=>10,
						   'BC'=>7,'BD'=>8,'BE'=>10,'BF'=>4,'BG'=>7,'BH'=>5,'BK'=>7,'BL'=>8,'BM'=>4,'BN'=>15,
						   'CD'=>9,'CE'=>6,'CF'=>10,'CG'=>7,'CH'=>12,'CK'=>5,'CL'=>9,'CM'=>6,'CN'=>11,
						   'DE'=>4,'DF'=>12,'DG'=>3,'DH'=>7,'DK'=>5,'DL'=>8,'DM'=>12,'DN'=>11,
						   'EF'=>5,'EG'=>8,'EH'=>9,'EK'=>4,'EL'=>7,'EM'=>3,'EN'=>12,
						   'FG'=>8,'FH'=>4,'FK'=>6,'FL'=>9,'FM'=>5,'FN'=>7,
						   'GH'=>10,'GK'=>5,'GL'=>6,'GM'=>7,'GN'=>8,
						   'HK'=>9,'HL'=>8,'HM'=>5,'HN'=>7,
						   'KL'=>6,'KM'=>8,'KN'=>9,
						   'LM'=>5,'LN'=>7,
						   'MN'=>9,
						));
*/