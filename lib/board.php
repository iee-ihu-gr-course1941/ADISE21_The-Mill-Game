<?php

function show_board($input) {
    global $mysqli;
	
	if($input==null){
		return null;}
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

		if($count >= 3){
		switch($x){
				case '1': 
					switch($y){

						case '1': $number_of_moves+=move_outercirle ($board,$b,$x,$y);break;
						case '2': break;
						case '3': break;
						case '4': $number_of_moves+=move_outercrossroad ($board,$b,$x,$y);break;
						case '5': break;
						case '6': break;
						case '7': $number_of_moves+=move_outercirle ($board,$b,$x,$y);break;
					}	
						break;
					switch($y){
				case '2':
						case '1': break;
						case '2': $number_of_moves+=move_semicirle ($board,$b,$x,$y);break;
						case '3': break;
						case '4': $number_of_moves+=move_crossroad ($board,$b,$x,$y);break;
						case '5': break;
						case '6': $number_of_moves+=move_semicirle ($board,$b,$x,$y);break;
						case '7': break;
					}	
						break;
				switch($y){
				case '3':
						case '1': break;
						case '2': break;
						case '3': $number_of_moves+=move_innercirle ($board,$b,$x,$y);break;
						case '4': $number_of_moves+=move_innercirle ($board,$b,$x,$y);break;
						case '5': $number_of_moves+=move_innercirle ($board,$b,$x,$y);break;
						case '6': break;
						case '7': break;
					}	
						break;
				switch($y){
				case '4':
						case '1': $number_of_moves+=move_outercrossroad ($board,$b,$x,$y);break;
						case '2': $number_of_moves+=move_crossroad ($board,$b,$x,$y);break;
						case '3': $number_of_moves+=move_innercirle ($board,$b,$x,$y);break;
						case '4': break;
						case '5': $number_of_moves+=move_innercirle ($board,$b,$x,$y);break;
						case '6': $number_of_moves+=move_crossroad ($board,$b,$x,$y);break;
						case '7': $number_of_moves+=move_outercrossroad ($board,$b,$x,$y);break;
					}	
						break;
				switch($y){
				case '5':
						case '1': break;
						case '2': break;
						case '3': $number_of_moves+=move_innercirle ($board,$b,$x,$y);break;
						case '4': $number_of_moves+=move_innercirle ($board,$b,$x,$y);break;
						case '5': $number_of_moves+=move_innercirle ($board,$b,$x,$y);break;
						case '6': break;
						case '7': break;
					}	
						break;

				switch($y){
				case '6':
						case '1': break;
						case '2': $number_of_moves+=move_innercirle ($board,$b,$x,$y);break;
						case '3': break;
						case '4': $number_of_moves+=move_crossroad ($board,$b,$x,$y);break;
						case '5': break;
						case '6': $number_of_moves+=move_innercirle ($board,$b,$x,$y);break;
						case '7': break;
					}	
						break;
				switch($y){
				case '7':
						case '1': $number_of_moves+=move_outercirle ($board,$b,$x,$y);break;
						case '2': break;
						case '3': break;
						case '4': $number_of_moves+=move_outercrossroad ($board,$b,$x,$y);break;
						case '5': break;
						case '6': break;
						case '7': $number_of_moves+=move_outercirle ($board,$b,$x,$y);break;
					}	
						break;
			
		}
		//else flying moves
	}
	} 
	return($number_of_moves);

}

function move_outercirle($board,$b,$x,$y){

	$m = [
		[3,0],
		[-3,0],
		[0,3],
		[0,-3]
	];	
	return(pawnmoves($board,$b,$x,$y,$m));
	
}


function move_semicirle($board,$b,$x,$y){

	$m = [
		[2,0],
		[-2,0],
		[0,2],
		[0,-2]
	];	
	return(pawnmoves($board,$b,$x,$y,$m));
	
}

function move_innercirle($board,$b,$x,$y){

	$m = [
		[1,0],
		[-1,0],
		[0,1],
		[0,-1]
	];	
	return(pawnmoves($board,$b,$x,$y,$m));
	
}

function move_innercrossroad($board,$b,$x,$y){

	$m = [
		[2,0],
		[-2,0],
		[0,1],
		[0,-1]
	];		
	return(pawnmoves($board,$b,$x,$y,$m));
	
}

function move_outercrossroad($board,$b,$x,$y){

	$m = [
		[1,0],
		[-1,0],
		[0,3],
		[0,-3]
	];		
	return(pawnmoves($board,$b,$x,$y,$m));
	
}





function piece_placement($x,$y,$piece_color,$input){
	global $mysqli;
	$sql = 'update board set piece_color=? where X=? and Y=? ';
	$st = $mysqli->prepare($sql);
	$st->bind_param('iii',$piece_color,$x,$y);
	$st->execute();
	
	$username=$input['username'];
	$sql = ' update players set playerNumber = playernumber + 1 where username=? ';
	$st3 = $mysqli->prepare($sql);
	$st3->bind_param('i',$username);
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
	$st->bind_param('iiiii',$x,$y,$x2,$y2,$pcolor); 
	$st->execute();

	header('Content-type: application/json');
	print json_encode(read_board(), JSON_PRETTY_PRINT);
}

function pawnmoves(&$board,$b,$x,$y,$m) {
	$moves=[];
	foreach($m as $k=>$t) {
		$x2=$x+$t[0];
		$y2=$y+$t[1];
		if( $x2>=1 && $x2<=7 && $y2>=1 && $y2<=7 &&
			$board[$x2][$y2]['piece_color'] !=$b ) {
			$move=['x'=>$x2, 'y'=>$y2];
			$moves[]=$move;
		}
	}
	$board[$x][$y]['moves'] = $moves;
	return(sizeof($moves));
}


function removepiece($x,$y,$piece_color,$input){
	global $mysqli;
	$sql = ' update board set piece_color=null where X=? and Y=? ';
	$st = $mysqli->prepare($sql);
	$st->bind_param('ii',$x,$y);
	$st->execute();
		
	//na alazei o arithmos pionion
}


?>