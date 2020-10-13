<?php

class Person 
{
	private $id;

	public function __construct( $id )
	{
		$this->id = $id;
	}

	public function getId() 
	{
		return $this->id;
	}
}


class ExperimentRoom
{
	private $groups;
	private $groupSize;

	private $sourceQueue;

	public function __construct( $groupsCount = 2, $groupSize = 10 )
	{
		/* Создаем множество групп */
		$this->groups = array_fill(0, $groupsCount, array());

		/* Создаем множество людей */
		$this->sourceQueue = array();
		for($i=0; $i < $groupsCount * $groupSize; $i++)
		{
			$this->sourceQueue[$i] = new Person($i+1);
		}

		$this->groupSize = $groupSize;
	}

	public function startExperiment( $times = 1 )
	{
		/* Количество успешных результатов */
		$successResults = 0;

		for($exnum=0; $exnum < $times; $exnum++)
		{

			/* 	Перемешиваем очередь случайным образом */
			shuffle( $this->sourceQueue );

			/*	Размещаем людей по группам.
				В случае непустых групп, просто перезаписываем поверх текущих ссылок */
			foreach( $this->sourceQueue as $idx => $person )
			{
				$this->groups[ floor($idx / $this->groupSize) ][ $idx % $this->groupSize ] = $person;
			}
			
			/* Проверяем, находятся ли 19 и 20 в одной группе */
			if( $this->inSameGroup([19, 20]) )
				$successResults++;
		}

		return $successResults;
	}

	private function inSameGroup( $ids ) 
	{

		/* Перебираем группы по списку */
		foreach( $this->groups as $group )
		{
			/*
				Количество искомых ID, найденных в рамках текущей группы.
				Если оно в момент перебора участников группы становится равным количеству переданных ID, то все искомые участники в одной группе.
				Если после перебора всех участников одной группы оно меньше количества переданных ID, но больше нуля => участники в разных группах.
			*/
			$personInSameGroup = 0;

			/* И каждого участника группы */
			foreach( $group as $person )
			{
				/* Проверяем, попадает ли участник в множество тех, кого мы ищем */
				if( in_array($person->getId(), $ids) )
				{
					$personInSameGroup++;
					if( $personInSameGroup === count($ids) ) 
						return true;
				}
			}
			
			if( $personInSameGroup > 0 && $personInSameGroup < count($ids) )
				return false;			
		}

		return false;
	}
}


/* 
	Выводим результаты эксперимента.
	`Из 10000000 экспериментов, 19 и 20 оказались в одной группе 4736232 раз. Вероятность: 0.4736232`

*/

$exRoom = new ExperimentRoom();

$times = 10000;
$success = $exRoom->startExperiment($times);

echo "Из $times экспериментов, 19 и 20 оказались в одной группе $success раз. Вероятность: ".( $success / $times );

?>
