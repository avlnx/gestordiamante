<?php

class Ago {

    public static function agolize($time_str) {
    	$date = new DateTime();
		$date->setTimestamp(strtotime($time_str));
		$interval = $date->diff(new DateTime('now'));
		if ($interval->y > 0)
		{
			echo $interval->format('%y Anos e %m meses atrás');
		} else if ($interval->m > 0) {
			echo $interval->format('%m Meses e %d dias atrás');
		} else if ($interval->d > 0) {
			echo $interval->format('%d Dias e %h horas atrás');
		} else if ($interval->i > 0) {
			echo $interval->format('%i Minutos atrás');
		} else {
			echo $interval->format('Menos de 1 minuto');
		}
		
    }
}