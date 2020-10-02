<?php 

/*
1) Данные по часам работы для каждого работника в каждый день заполнялась при помощи команды FLOOR(8*RAND()) (от 0 до 8 рабочих часов в день)
2) Для Olivia часы ввелись вручную дробные, для демонстрации округления до 2 знаков после запятой, при выводе информации
3) При отсутсвии нужного числа работников (3), при выводе покажет сколько есть, будь то 1 или 2.
   При отсутсвии данных выведет сообщение напротив запрашиваемого дня недели
4) "`date` is a date in US format M/d/YYYY" формат даты нельзя определить во время создания, можно изменить его отображение при необходимости выборки дат при помощи DATE_FORMAT(date, '%m/%d/%Y'). Программа не использует вывыод непосредственно даты, а только определяет день недели по существующим датам. 
 */

require ('config.php');

class Info
{

	private $week = [
		"Monday",
		"Tuesday",
		"Wednesday",
		"Thursday",
		"Friday",
		"Saturday",
		"Sunday"
	];

	function __construct() {
		foreach ($this->week as $day) {
		$this->getDayInfo($day);
		}
	}

	private function getDayInfo($day)
	{
		global $mysqli;
		$stmt = $mysqli->prepare("SELECT employees.name, ROUND(AVG(time_reports.hours),2) as 'hours' FROM time_reports
				JOIN employees ON employees.id = time_reports.employee_id
				WHERE DAYNAME(time_reports.date) = ?
				GROUP BY employees.name
				ORDER BY AVG(time_reports.hours) DESC LIMIT 3");

		$stmt->bind_param("s", $day);
		$stmt->execute();
		$stmt->store_result();
		$count = $stmt->num_rows;
		$stmt->bind_result($name, $hours);
		
		echo "| {$day} | ";

		if($count == 0){
			echo "Для данного дня нет данных" . PHP_EOL;
		}
		
		$i=1;
		while ($stmt->fetch()){
			switch ($count) {
				case 1:
					echo $name . " ({$hours}) |" . PHP_EOL;
					continue 2;
				case 2:
					if($i == 2) {
						echo $name . " ({$hours}) |" . PHP_EOL;
					}else {
						echo $name . " ({$hours}), ";
					}
					$i++;
					continue 2;
				case 3:
					if($i == 3) {
						echo $name . " ({$hours}) |" . PHP_EOL;
					}else {
						echo $name . " ({$hours}), ";
					}
					$i++;
					continue 2;		
			}	
		}
		
		$stmt->close();
	}

}

$info = new Info;

$mysqli->close();
