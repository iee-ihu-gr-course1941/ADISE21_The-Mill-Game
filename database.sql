
DROP TABLE IF EXISTS `board`;
DROP TABLE IF EXISTS `boardempty`;
DROP TABLE IF EXISTS `game_status`;
DROP TABLE IF EXISTS `players`;
DROP PROCEDURE IF EXISTS clean_board;
DROP PROCEDURE IF EXISTS piece_placement;
DROP PROCEDURE IF EXISTS piece_movement;
DROP TRIGGER if EXISTS game_status_update;


CREATE TABLE `board` ( 
`x` varchar(1) NOT NULL, 
`y` tinyint(1) NOT NULL,
`piece_color` enum('W','B') DEFAULT NULL,
`boardslot` boolean NOT NULL, 
 PRIMARY KEY (`x`,`y`) )ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `boardempty` (
`x` varchar(1) NOT NULL, 
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
('A',1,NULL,TRUE),
('A',2,NULL,FALSE),
('A',3,NULL,FALSE),
('A',4,NULL,TRUE),
('A',5,NULL,FALSE),
('A',6,NULL,FALSE),
('A',7,NULL,TRUE),
('B',1,NULL,FALSE),
('B',2,NULL,TRUE),
('B',3,NULL,FALSE),
('B',4,NULL,TRUE),
('B',5,NULL,FALSE),
('B',6,NULL,TRUE),
('B',7,NULL,FALSE),
('C',1,NULL,FALSE),
('C',2,NULL,FALSE),
('C',3,NULL,TRUE),
('C',4,NULL,TRUE),
('C',5,NULL,TRUE),
('C',6,NULL,FALSE),
('C',7,NULL,FALSE),
('D',1,NULL,TRUE),
('D',2,NULL,TRUE),
('D',3,NULL,TRUE),
('D',4,NULL,FALSE),
('D',5,NULL,TRUE),
('D',6,NULL,TRUE),
('D',7,NULL,TRUE),
('E',1,NULL,FALSE),
('E',2,NULL,FALSE),
('E',3,NULL,TRUE),
('E',4,NULL,TRUE),
('E',5,NULL,TRUE),
('E',6,NULL,FALSE),
('E',7,NULL,FALSE),
('F',1,NULL,FALSE),
('F',2,NULL,TRUE),
('F',3,NULL,FALSE),
('F',4,NULL,TRUE),
('F',5,NULL,FALSE),
('F',6,NULL,TRUE),
('F',7,NULL,FALSE),
('G',1,NULL,TRUE),
('G',2,NULL,FALSE),
('G',3,NULL,FALSE),
('G',4,NULL,TRUE),
('G',5,NULL,FALSE),
('G',6,NULL,FALSE),
('G',7,NULL,TRUE);



INSERT INTO boardempty VALUES 
('A',1,NULL,TRUE),
('A',2,NULL,FALSE),
('A',3,NULL,FALSE),
('A',4,NULL,TRUE),
('A',5,NULL,FALSE),
('A',6,NULL,FALSE),
('A',7,NULL,TRUE),
('B',1,NULL,FALSE),
('B',2,NULL,TRUE),
('B',3,NULL,FALSE),
('B',4,NULL,TRUE),
('B',5,NULL,FALSE),
('B',6,NULL,TRUE),
('B',7,NULL,FALSE),
('C',1,NULL,FALSE),
('C',2,NULL,FALSE),
('C',3,NULL,TRUE),
('C',4,NULL,TRUE),
('C',5,NULL,TRUE),
('C',6,NULL,FALSE),
('C',7,NULL,FALSE),
('D',1,NULL,TRUE),
('D',2,NULL,TRUE),
('D',3,NULL,TRUE),
('D',4,NULL,FALSE),
('D',5,NULL,TRUE),
('D',6,NULL,TRUE),
('D',7,NULL,TRUE),
('E',1,NULL,FALSE),
('E',2,NULL,FALSE),
('E',3,NULL,TRUE),
('E',4,NULL,TRUE),
('E',5,NULL,TRUE),
('E',6,NULL,FALSE),
('E',7,NULL,FALSE),
('F',1,NULL,FALSE),
('F',2,NULL,TRUE),
('F',3,NULL,FALSE),
('F',4,NULL,TRUE),
('F',5,NULL,FALSE),
('F',6,NULL,TRUE),
('F',7,NULL,FALSE),
('G',1,NULL,TRUE),
('G',2,NULL,FALSE),
('G',3,NULL,FALSE),
('G',4,NULL,TRUE),
('G',5,NULL,FALSE),
('G',6,NULL,FALSE),
('G',7,NULL,TRUE);

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
CREATE PROCEDURE piece_placement(pcolor char, x1 TINYINT, y1 TINYINT)
BEGIN
UPDATE board
SET piece_color	= pcolor
WHERE x = x1 AND y = y1;

UPDATE game_status set p_turn=if(p_color='W','B','W');

END$$

DELIMITER ;



DELIMITER $$ 

CREATE PROCEDURE piece_movement(x1 tinyint, y1 tinyint, x2 tinyint, y2 tinyint, pcolor char)
BEGIN
UPDATE board
set piece_color = null
where x= x1 AND y = y1;

UPDATE board
set piece_color = pcolor
where x = x2 AND y = y2;
UPDATE game_status set p_turn=if(p_color='W','B','W');


END$$ 

DELIMITER ;