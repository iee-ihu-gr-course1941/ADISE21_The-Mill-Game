DROP TABLE IF EXISTS `board`;
DROP TABLE IF EXISTS `boardempty`;
DROP TABLE IF EXISTS `game_status`;
DROP TABLE IF EXISTS `players`;
DROP PROCEDURE IF EXISTS clean_board;
DROP PROCEDURE IF EXISTS piece_placement;
DROP PROCEDURE IF EXISTS piece_movement;
DROP PROCEDURE IF EXISTS turnupdate;
DROP TRIGGER if EXISTS game_status_update;


CREATE TABLE `board` ( 
`x` tinyint(1) NOT NULL, 
`y` tinyint(1) NOT NULL,
`piece_color` enum('W','B') DEFAULT NULL,
`boardslot` boolean NOT NULL, 
 PRIMARY KEY (`x`,`y`) )ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `boardempty` (
`x` tinyint(1) NOT NULL, 
`y` tinyint(1) NOT NULL,
`piece_color` enum('W','B') DEFAULT NULL,
`boardslot` boolean NOT NULL, 
 PRIMARY KEY (`x`,`y`) )ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `players` ( 
`username` varchar(20) DEFAULT NULL, 
`piece_color` enum('W','B') NOT NULL,
`token` varchar(100) DEFAULT NULL,
`piece_number` int DEFAULT 9,
PRIMARY KEY (`piece_color`) )ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `game_status` (
`status` enum('not active','initialized','started','ended','aborded') NOT NULL DEFAULT 'not active',
`p_turn` enum('W','B') DEFAULT NULL,
`result` enum('B','W','D') DEFAULT NULL,
`last_change` timestamp NULL DEFAULT NULL )ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO board  VALUES 
(1,1,NULL,TRUE),
(1,2,NULL,FALSE),
(1,3,NULL,FALSE),
(1,4,NULL,TRUE),
(1,5,NULL,FALSE),
(1,6,NULL,FALSE),
(1,7,NULL,TRUE),
(2,1,NULL,FALSE),
(2,2,NULL,TRUE),
(2,3,NULL,FALSE),
(2,4,NULL,TRUE),
(2,5,NULL,FALSE),
(2,6,NULL,TRUE),
(2,7,NULL,FALSE),
(3,1,NULL,FALSE),
(3,2,NULL,FALSE),
(3,3,NULL,TRUE),
(3,4,NULL,TRUE),
(3,5,NULL,TRUE),
(3,6,NULL,FALSE),
(3,7,NULL,FALSE),
(4,1,NULL,TRUE),
(4,2,NULL,TRUE),
(4,3,NULL,TRUE),
(4,4,NULL,FALSE),
(4,5,NULL,TRUE),
(4,6,NULL,TRUE),
(4,7,NULL,TRUE),
(5,1,NULL,FALSE),
(5,2,NULL,FALSE),
(5,3,NULL,TRUE),
(5,4,NULL,TRUE),
(5,5,NULL,TRUE),
(5,6,NULL,FALSE),
(5,7,NULL,FALSE),
(6,1,NULL,FALSE),
(6,2,NULL,TRUE),
(6,3,NULL,FALSE),
(6,4,NULL,TRUE),
(6,5,NULL,FALSE),
(6,6,NULL,TRUE),
(6,7,NULL,FALSE),
(7,1,NULL,TRUE),
(7,2,NULL,FALSE),
(7,3,NULL,FALSE),
(7,4,NULL,TRUE),
(7,5,NULL,FALSE),
(7,6,NULL,FALSE),
(7,7,NULL,TRUE);



INSERT INTO boardempty VALUES 
(1,1,NULL,TRUE),
(1,2,NULL,FALSE),
(1,3,NULL,FALSE),
(1,4,NULL,TRUE),
(1,5,NULL,FALSE),
(1,6,NULL,FALSE),
(1,7,NULL,TRUE),
(2,1,NULL,FALSE),
(2,2,NULL,TRUE),
(2,3,NULL,FALSE),
(2,4,NULL,TRUE),
(2,5,NULL,FALSE),
(2,6,NULL,TRUE),
(2,7,NULL,FALSE),
(3,1,NULL,FALSE),
(3,2,NULL,FALSE),
(3,3,NULL,TRUE),
(3,4,NULL,TRUE),
(3,5,NULL,TRUE),
(3,6,NULL,FALSE),
(3,7,NULL,FALSE),
(4,1,NULL,TRUE),
(4,2,NULL,TRUE),
(4,3,NULL,TRUE),
(4,4,NULL,FALSE),
(4,5,NULL,TRUE),
(4,6,NULL,TRUE),
(4,7,NULL,TRUE),
(5,1,NULL,FALSE),
(5,2,NULL,FALSE),
(5,3,NULL,TRUE),
(5,4,NULL,TRUE),
(5,5,NULL,TRUE),
(5,6,NULL,FALSE),
(5,7,NULL,FALSE),
(6,1,NULL,FALSE),
(6,2,NULL,TRUE),
(6,3,NULL,FALSE),
(6,4,NULL,TRUE),
(6,5,NULL,FALSE),
(6,6,NULL,TRUE),
(6,7,NULL,FALSE),
(7,1,NULL,TRUE),
(7,2,NULL,FALSE),
(7,3,NULL,FALSE),
(7,4,NULL,TRUE),
(7,5,NULL,FALSE),
(7,6,NULL,FALSE),
(7,7,NULL,TRUE);

DELIMITER $$ 

CREATE TRIGGER game_status_update BEFORE UPDATE ON 
game_status FOR EACH ROW BEGIN SET
NEW.last_change = NOW(); 
END$$ DELIMITER ;

DELIMITER $$ 

CREATE PROCEDURE clean_board() 
BEGIN 
REPLACE INTO board SELECT * FROM boardempty; 
UPDATE `players` set username=null, token=null, piece_number=9;
UPDATE`game_status` set `status`='not active', `p_turn`="W", `result`=null;
END$$ 
DELIMITER ;



DELIMITER	$$
CREATE PROCEDURE piece_placement(x1 TINYINT, y1 TINYINT)
BEGIN

select piece_color,boardslot from board where X= x1 and Y= y1 and boardslot = 1;
UPDATE board
SET piece_color	= piece_color
WHERE x = x1 AND y = y1 and boardslot = 1 ;

UPDATE game_status set p_turn=if(piece_color='W','B','W');

END$$

DELIMITER ;



DELIMITER $$ 

CREATE PROCEDURE piece_movement(x1 tinyint, y1 tinyint, x2 tinyint, y2 tinyint )
BEGIN

select piece_color,boardslot from board where X= x1 and Y= y1 and boardslot = 1;

UPDATE board
set piece_color = piece_color
where x = x2 AND y = y2 and boardslot = 1;

UPDATE board
set piece_color = null
where x= x1 AND y = y1 and boardslot = 1;

UPDATE game_status set p_turn=if(piece_color='W','B','W');


END$$ 

DELIMITER ;

DELIMITER $$ 

CREATE PROCEDURE turnupdate(pcolor char)
BEGIN
UPDATE game_status
set p_turn = if(pcolor='W','B','W');

END$$ 

DELIMITER ;


