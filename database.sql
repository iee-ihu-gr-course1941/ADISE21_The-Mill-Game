

CREATE TABLE `board` ( 
`x` tinyint(1) NOT NULL, 
`y` tinyint(1) NOT NULL,
`piece_color` enum('W','B') DEFAULT NULL,
`Boardslot` enum('T','F'), 
 PRIMARY KEY (`x`,`y`) );


DROP TABLE IF EXISTS `boardempty`;

CREATE TABLE `boardempty` (
`x` tinyint(1) NOT NULL, 
`y` tinyint(1) NOT NULL,
`piece_color` enum('W','B') DEFAULT NULL,
`boardslot` enum('T','F'), 
 PRIMARY KEY (`x`,`y`) );

CREATE TABLE `players` ( 
`username` varchar(20) DEFAULT NULL, 
`piece_color` enum('B','W') NOT NULL,
`token` varchar(40) DEFAULT NULL,
PRIMARY KEY (`token`) )

CREATE TABLE `game_status` (
`status` enum('not active','initialized','started','ended','aborded') NOT NULL DEFAULT 'not active',
`p_turn` enum('W','B') DEFAULT NULL,
`result` enum('B','W','D') DEFAULT NULL,
`last_change` timestamp NULL DEFAULT NULL )

