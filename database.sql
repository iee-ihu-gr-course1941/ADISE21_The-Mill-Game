
DROP TABLE IF EXISTS `board`;
DROP TABLE IF EXISTS `boardempty`;
DROP TABLE IF EXISTS `game_status`;
DROP TABLE IF EXISTS `players`;


CREATE TABLE `board` ( 
`x` varchar(1) NOT NULL, 
`y` tinyint(1) NOT NULL,
`piece_color` enum('W','B') DEFAULT NULL,
`boardslot` enum('T','F') NOT NULL, 
 PRIMARY KEY (`x`,`y`) )ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `boardempty` (
`x` varchar(1) NOT NULL, 
`y` tinyint(1) NOT NULL,
`piece_color` enum('W','B') DEFAULT NULL,
`boardslot` enum('T','F')NOT NULL, 
 PRIMARY KEY (`x`,`y`) )ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `players` ( 
`username` varchar(20) DEFAULT NULL, 
`piece_color` enum('B','W') NOT NULL,
`token` varchar(40) DEFAULT NULL,
`piece_number` int(9) DEFAULT 9,
PRIMARY KEY (`piece_color`) )ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `game_status` (
`status` enum('not active','initialized','started','ended','aborded') NOT NULL DEFAULT 'not active',
`p_turn` enum('W','B') DEFAULT NULL,
`result` enum('B','W','D') DEFAULT NULL,
`last_change` timestamp NULL DEFAULT NULL )ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO board  VALUES 
('A',1,NULL,'T'),
('A',2,NULL,'F'),
('A',3,NULL,'F'),
('A',4,NULL,'T'),
('A',5,NULL,'F'),
('A',6,NULL,'F'),
('A',7,NULL,'T'),
('B',1,NULL,'F'),
('B',2,NULL,'T'),
('B',3,NULL,'F'),
('B',4,NULL,'T'),
('B',5,NULL,'F'),
('B',6,NULL,'T'),
('B',7,NULL,'F'),
('C',1,NULL,'F'),
('C',2,NULL,'F'),
('C',3,NULL,'T'),
('C',4,NULL,'T'),
('C',5,NULL,'T'),
('C',6,NULL,'F'),
('C',7,NULL,'F'),
('D',1,NULL,'T'),
('D',2,NULL,'T'),
('D',3,NULL,'T'),
('D',4,NULL,'F'),
('D',5,NULL,'T'),
('D',6,NULL,'T'),
('D',7,NULL,'T'),
('E',1,NULL,'F'),
('E',2,NULL,'F'),
('E',3,NULL,'T'),
('E',4,NULL,'T'),
('E',5,NULL,'T'),
('E',6,NULL,'F'),
('E',7,NULL,'F'),
('F',1,NULL,'F'),
('F',2,NULL,'T'),
('F',3,NULL,'F'),
('F',4,NULL,'T'),
('F',5,NULL,'F'),
('F',6,NULL,'T'),
('F',7,NULL,'F'),
('G',1,NULL,'T'),
('G',2,NULL,'F'),
('G',3,NULL,'F'),
('G',4,NULL,'T'),
('G',5,NULL,'F'),
('G',6,NULL,'F'),
('G',7,NULL,'T');



INSERT INTO boardempty VALUES 
('A',1,NULL,'T'),
('A',2,NULL,'F'),
('A',3,NULL,'F'),
('A',4,NULL,'T'),
('A',5,NULL,'F'),
('A',6,NULL,'F'),
('A',7,NULL,'T'),
('B',1,NULL,'F'),
('B',2,NULL,'T'),
('B',3,NULL,'F'),
('B',4,NULL,'T'),
('B',5,NULL,'F'),
('B',6,NULL,'T'),
('B',7,NULL,'F'),
('C',1,NULL,'F'),
('C',2,NULL,'F'),
('C',3,NULL,'T'),
('C',4,NULL,'T'),
('C',5,NULL,'T'),
('C',6,NULL,'F'),
('C',7,NULL,'F'),
('D',1,NULL,'T'),
('D',2,NULL,'T'),
('D',3,NULL,'T'),
('D',4,NULL,'F'),
('D',5,NULL,'T'),
('D',6,NULL,'T'),
('D',7,NULL,'T'),
('E',1,NULL,'F'),
('E',2,NULL,'F'),
('E',3,NULL,'T'),
('E',4,NULL,'T'),
('E',5,NULL,'T'),
('E',6,NULL,'F'),
('E',7,NULL,'F'),
('F',1,NULL,'F'),
('F',2,NULL,'T'),
('F',3,NULL,'F'),
('F',4,NULL,'T'),
('F',5,NULL,'F'),
('F',6,NULL,'T'),
('F',7,NULL,'F'),
('G',1,NULL,'T'),
('G',2,NULL,'F'),
('G',3,NULL,'F'),
('G',4,NULL,'T'),
('G',5,NULL,'F'),
('G',6,NULL,'F'),
('G',7,NULL,'T');

insert into players VALUES 
(Null,'W',null,9),
(Null,'B',null,9);

DELIMITER $$ CREATE TRIGGER game_status_update BEFORE UPDATE ON game_status FOR EACH ROW BEGIN SET NEW.last_change = NOW(); END$$ DELIMITER ;

DELIMITER $$ CREATE PROCEDURE clean_board() 
BEGIN REPLACE INTO board SELECT * FROM boardempty; 
update `players` set username=null, token=null;
update`game_status` set `status`='not active', `p_turn`=null, `result`=null;
END$$ DELIMITER ;



DELIMITER $$ CREATE PROCEDURE piece_placement()




END$$ DELIMITER;



DELIMITER $$ CREATE PROCEDURE piece_movent()


END$$ DELIMITER;