<?php

include("TSP.class.php");
include("TSPUtils.class.php");

$nodes = range('A','H');

#---> randomly create nodes and distances
$TSPUtils = new TSPUtils( $nodes );
$TSPUtils->populate_sides();
$distances = $TSPUtils->get_sides();

/* example for distances
 *  array('AB'=>3,'AC'=>6,'AD'=>9,'AE'=>5,'AF'=>6,'AG'=>8,'AH'=>11,
 *						   'BC'=>7,'BD'=>8,'BE'=>10,'BF'=>4,'BG'=>7,'BH'=>5,
 *						   'CD'=>9,'CE'=>6,'CF'=>10,'CG'=>7,'CH'=>12,
 *						   'DE'=>4,'DF'=>12,'DG'=>3,'DH'=>7,
 *						   'EF'=>5,'EG'=>8,'EH'=>9,
 *						   'FG'=>8,'FH'=>4,
 *						   'GH'=>10) 
 * 
 */
	
$TSP = new TSP( $nodes );
$TSP->set_distances( $distances );
$TSP->set_starting_point('A');
$TSP->get_shortest_path();

$TSP->display_route();
$TSP->display_journey();
$TSP->display_total_distance();






?>