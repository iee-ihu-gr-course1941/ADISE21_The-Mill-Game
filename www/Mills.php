<?php
require_once "../lib/dbconnect.php";
require_once "../lib/board.php";
require_once "../lib/game.php";
require_once "../lib/users.php";



$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'),true);
if($input==null) {
    $input=[];
}
if(isset($_SERVER['HTTP_X_TOKEN'])) {
    $input['token']=$_SERVER['HTTP_X_TOKEN'];
} else {
    $input['token']='';
}



switch ($r=array_shift($request)) {
    case 'board' : 
        switch ($b=array_shift($request)) {
            case '':
            case null: handle_board($method,$input);
                        break;
            case 'piece': handle_piece($method, $request[0],$request[1], $request[2],$request[3],$input);
                        break;
            case 'removepiece': handle_removepiece($method, $request[0],$request[1],$input);
                        break;
            case 'counterpiece': handle_counterpiece($method, $request[0]);            
                        break;
              
            default: header("HTTP/1.1 404 Not Found");
                        break;
			}
            break;
    case 'status':
			if(sizeof($request)==0) {handle_status($method);}
			else {header("HTTP/1.1 404 Not Found");}
			break;
	case 'players': handle_player($method, $request,$input);
			    break;
	default:  header("HTTP/1.1 404 Not Found");
                        exit;
}


function handle_board($method,$input) {
    if($method=='GET') {
            show_board($input);
    } else if ($method=='POST') {
            reset_board($input);
    } else {
        header('HTTP/1.1 405 Method Not Allowed');
    }
    
}

    function handle_piece($method, $x,$y,$x2,$y2,$input) {
        if($x2==null && $y2== null)
            if($method == 'GET')
                show_piece($x,$y);
            elseif($method == "PUT")
                piece_placement($x, $y,$input);
            
            else    
            header('HTTP/1.1 405 Method Not Allowed');
        elseif($method == "PUT")
            move_piece($x,$y,$x2,$y2,$input['token']);
        else
        
            header('HTTP/1.1 405 Method Not Allowed');
     
}

function handle_player($method, $p,$input) {
    switch ($b=array_shift($p)) {
	
        case 'B': 
		case 'W': handle_user($method, $b,$input);
					break;
		default: header("HTTP/1.1 404 Not Found");
				 print json_encode(['errormesg'=>"Player $b not found."]);
                 break;
	}
}

function handle_status($method) {
    if($method=='GET') {
        show_status();
    } else {
        header('HTTP/1.1 405 Method Not Allowed');
    }

    
}

function handle_removepiece($method , $x, $y, $input){
	if($method=='POST') {
        removepiece($x, $y, $input);
    } else {
        header('HTTP/1.1405 Method Not Allowed');
    }
}

function handle_counterpiece($method, $input){
	if($method=='GET') {
         currentpieces($input);
    } else {
        header('HTTP/1.1405 Method Not Allowed');
    }
}























?>