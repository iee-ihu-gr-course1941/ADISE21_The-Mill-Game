<?php

function show_board() {
    global $mysqli;
	
	$sql = 'select * from board';
	$st = $mysqli->prepare($sql);

	$st->execute();
	$res = $st->get_result();

	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}


function reset_board() {
	global $mysqli;
	
	$sql = 'call clean_board()';
	$mysqli->query($sql);
	show_board();
}

function read_board() {
	global $mysqli;
	$sql = 'select * from board';
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	return($res->fetch_all(MYSQLI_ASSOC));
}


function convert_board(&$orig_board) {
	$board=[];
	foreach($orig_board as $i=>&$row) {
		$board[$row['x']][$row['y']] = &$row;
	} 
	return($board);
}


function show_piece($x,$y) {
	global $mysqli;
	
	$sql = 'select * from board where x=? and y=?';
	$st = $mysqli->prepare($sql);
	$st->bind_param('ii',$x,$y);
	$st->execute();
	$res = $st->get_result();
	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}


function add_valid_moves_to_piece(&$board,$b,$x,$y) {
	$number_of_moves=0;
	$count = currentpieces($token);
	if($board[$x][$y]['piece_color']==$b) {

		if($count >= 2){
		switch($board[$x]){
				case 'A': 
					switch($y){

						case '1': $number_of_moves+=move_a1 ($board,$b,$x,$y);break;
						case '2': break;
						case '3': break;
						case '4': $number_of_moves+=move_a4 ($board,$b,$x,$y);break;
						case '5': break;
						case '6': break;
						case '7': $number_of_moves+=move_a7 ($board,$b,$x,$y);break;
					}	
						break;
					switch($y){
				case 'B':
						case '1': break;
						case '2': $number_of_moves+=move_b2 ($board,$b,$x,$y);break;
						case '3': break;
						case '4': $number_of_moves+=move_b4 ($board,$b,$x,$y);break;
						case '5': break;
						case '6': $number_of_moves+=move_b6 ($board,$b,$x,$y);break;
						case '7': break;
					}	
						break;
				switch($y){
				case 'C':
						case '1': break;
						case '2': break;
						case '3': $number_of_moves+=move_c3 ($board,$b,$x,$y);break;;
						case '4': $number_of_moves+=move_c4 ($board,$b,$x,$y);break;
						case '5': $number_of_moves+=move_c5 ($board,$b,$x,$y);break;
						case '6': break;
						case '7': break;
					}	
						break;
				switch($y){
				case 'D':
						case '1': $number_of_moves+=move_d1 ($board,$b,$x,$y);break;
						case '2': $number_of_moves+=move_d2 ($board,$b,$x,$y);break;
						case '3': $number_of_moves+=move_d3 ($board,$b,$x,$y);break;
						case '4': break;
						case '5': $number_of_moves+=move_d5 ($board,$b,$x,$y);break;
						case '6': $number_of_moves+=move_d6 ($board,$b,$x,$y);break;
						case '7': $number_of_moves+=move_d7 ($board,$b,$x,$y);break;
					}	
						break;
				switch($y){
				case 'E':
						case '1': break;
						case '2': break;
						case '3': $number_of_moves+=move_e3 ($board,$b,$x,$y);break;;
						case '4': $number_of_moves+=move_e4 ($board,$b,$x,$y);break;
						case '5': $number_of_moves+=move_e5 ($board,$b,$x,$y);break;
						case '6': break;
						case '7': break;
					}	
						break;

				switch($y){
				case 'F':
						case '1': break;
						case '2': $number_of_moves+=move_f2 ($board,$b,$x,$y);break;
						case '3': break;
						case '4': $number_of_moves+=move_f4 ($board,$b,$x,$y);break;
						case '5': break;
						case '6': $number_of_moves+=move_f6 ($board,$b,$x,$y);break;
						case '7': break;
					}	
						break;
				switch($y){
				case 'G':
						case '1': $number_of_moves+=move_g1 ($board,$b,$x,$y);break;
						case '2': break;
						case '3': break;
						case '4': $number_of_moves+=move_g4 ($board,$b,$x,$y);break;
						case '5': break;
						case '6': break;
						case '7': $number_of_moves+=move_g7 ($board,$b,$x,$y);break;
					}	
						break;
			
		}
	}
	} 
	return($number_of_moves);



function move_a1($board,$b,$x,$y){


	if($x=$x && $y=$y){


	}
	//den prepei na einai i thesi idia me tin proigoumeni alliws error






}











































?>