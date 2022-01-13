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


function move_piece($x,$y,$x2,$y2,$token) {
	
	if($token==null || $token=='') {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"token is not set."]);
		exit;
	}
	
	$color = current_color($token);
	if($color=='null' ) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"You are not a player of this game."]);
		exit;
	}
	
	$bcolor = current_boardslot($token, $x2, $y2);
	if($bcolor== FALSE ) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"You cant play there."]);
		exit;
	}
	$color = current_piececolor($token, $x2, $y2);
	if($color== 'W' || $color=='B' ) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"You cant play there.There is a piece"]);
		exit;
	}


	$status = read_status();
	if($status['status']!='started') {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"Game is not in action."]);
		exit;
	}
	if($status['p_turn']!=$color) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"It is not your turn."]);
		exit;
	}
	$orig_board=read_board();
	$board=convert_board($orig_board);
	$n = add_valid_moves_to_piece($board,$color,$x,$y);
	if($n==0) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"This piece cannot move."]);
		exit;
	}
	foreach($board[$x][$y]['moves'] as $i=>$move) {
		if($x2==$move['x'] && $y2==$move['y']) {
			do_move($x,$y,$x2,$y2);
			exit;
		}
	}
	header("HTTP/1.1 400 Bad Request");
	print json_encode(['errormesg'=>"This move is illegal."]);
	exit;
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
						case '3': $number_of_moves+=move_c3 ($board,$b,$x,$y);break;
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
						case '3': $number_of_moves+=move_e3 ($board,$b,$x,$y);break;
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

function piece_placement($x,$y,$piece_color,$input){
	global $mysqli;
	$sql = 'update board set piece_color=? where X=? and Y=? ';
	$st = $mysqli->prepare($sql);
	$st->bind_param('sss',$piece_color,$x,$y);
	$st->execute();
	
	$username=$input['username'];
	$sql = ' update players set playerNumber = playernumber + 1 where username=? ';
	$st3 = $mysqli->prepare($sql);
	$st3->bind_param('s',$username);
	$st3->execute();
	
	$sql = 'call `piece_placement`(?,?,?);';
	$st2 = $mysqli->prepare($sql);
	$st2->bind_param('iii',$x,$y);
	$st2->execute();
	
	header('Content-type: application/json');
	print json_encode(read_board(), JSON_PRETTY_PRINT);
	
}

function show_piecenumber($pic){
	global $mysqli;
	$sql = 'select piece_number from players where piece_color=? ';
	$st = $mysqli->prepare($sql);
	$st->bind_param('s',$pic);
	$st->execute();
	$res = $st->get_result();
	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}

function do_move($x,$y,$x2,$y2) {
	global $mysqli;
	$sql = 'call `piece_movement`(?,?,?,?,?);';
	$st = $mysqli->prepare($sql);
	$st->bind_param('iiiii',$x,$y,$x2,$y2 ); //ali mia parametro
	$st->execute();

	header('Content-type: application/json');
	print json_encode(read_board(), JSON_PRETTY_PRINT);
}









































?>