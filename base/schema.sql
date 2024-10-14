create database stock;
use stock;

CREATE TABLE input(
   Id_input INT AUTO_INCREMENT,
   daty DATE NOT NULL,
   qtt DECIMAL(15,2)   NOT NULL CHECK (qtt >= 0),
   pu DECIMAL(18,2)   NOT NULL CHECK (pu > 0),
   total DECIMAL(18,2)   NOT NULL CHECK (total >0),
   PRIMARY KEY(Id_input)
);

CREATE TABLE output(
   Id_output INT AUTO_INCREMENT,
   daty DATE NOT NULL,
   qtt DECIMAL(15,2)   NOT NULL CHECK (qtt >= 0),
   pu DECIMAL(18,2)   NOT NULL CHECK (pu > 0),
   total DECIMAL(18,2) NOT NULL CHECK (total >0),   
   Id_input INT NOT NULL,
   PRIMARY KEY(Id_output),
   FOREIGN KEY(Id_input) REFERENCES input(Id_input)
);

--reste stock
create or replace view current_stock as
select i.Id_input, ifnull(o.daty, i.daty) as output_date,
(i.qtt - ifnull(o.qtt, 0)) as qtt, i.pu , (i.qtt - ifnull(o.qtt, 0))*i.pu as total
from input i
left join (select Id_input, sum(qtt) as qtt, max(daty) as daty from output group by Id_input) o
on i.Id_input = o.Id_input
where (i.qtt - ifnull(o.qtt, 0)) > 0;

DELIMITER $$

CREATE PROCEDURE get_stock_by_date(IN dates DATE)
BEGIN
   SELECT IFNULL(o.daty, i.daty) AS output_date,
          (i.qtt - IFNULL(o.qtt, 0)) AS qtt, 
          i.pu, 
          (i.qtt - IFNULL(o.qtt, 0)) * i.pu AS total
   FROM input i
   LEFT JOIN (
      SELECT Id_input, SUM(qtt) AS qtt, MAX(daty) AS daty 
      FROM output 
      WHERE daty <= dates
      GROUP BY Id_input
   ) o ON i.Id_input = o.Id_input
   WHERE (i.qtt - IFNULL(o.qtt, 0)) > 0 
     AND i.daty <= dates;
END$$

DELIMITER ;

--CMUP

DELIMITER $$

CREATE PROCEDURE get_cmup() 
BEGIN
    SELECT 
        SUM(qtt) AS total_quantity, 
        SUM(total) AS total_value, 
        (SUM(total) / NULLIF(SUM(qtt), 0)) AS cmup 
    FROM current_stock;  -- Ajout du point-virgule ici
END$$

DELIMITER ;



-- SELECT SUM() from current_stock;


-- select ifnull(o.daty, i.daty) as output_date,
-- (i.qtt - ifnull(o.qtt, 0)) as qtt, i.pu , (i.qtt - ifnull(o.qtt, 0))*i.pu as total
-- from input i
-- left join (
--    select Id_input,  sum(qtt) as qtt, max(daty) as daty 
--    from output 
--    where daty <= '2024-10-12' 
--    group by Id_input) o
-- on i.Id_input = o.Id_input
-- where (i.qtt - ifnull(o.qtt, 0)) > 0 and i.daty <= '2024-10-12';