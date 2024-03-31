<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Extraction from B24</title>
</head>
<body>
	<div id="name">
		<?php
		require_once (__DIR__.'/crest.php');
        
        $fDay = date("d", strtotime("first day of last month"));
        $fMonth = date("m", strtotime("first day of last month"));
        $fYear = date("Y", strtotime("first day of last month"));
        $formattedDate = $fYear."-".$fMonth."-".$fDay."T00:00:00+03:00";
        
		$firstCall = CRest::call('tasks.task.list', ['filter' => ['>=CREATED_DATE' => $formattedDate], 'select' => ['ID']]);
		$total = intval($firstCall['total']);
		$formedArray = [];
	    
		for($i = 0; $i <= $total; $i += 50) {
			$iterationResult = CRest::call('tasks.task.list', ['filter' => ['>=CREATED_DATE' => $formattedDate, '!RESPONSIBLE_ID' => 1, ['LOGIC' => 'OR',  '!RESPONSIBLE_ID' => 12]], 'start' => $i]);
			$formedArray[] = $iterationResult['result']['tasks'];
		}
		
		foreach($formedArray as $formedArrayElement) {
			foreach($formedArrayElement as $formedArrayTask) {
 		        //echo "<pre>"; print_r($formedArrayTask); echo "</pre>";
  		        $taskDate = substr($formedArrayTask['createdDate'], 0, 10);
   		        $taskStatus = empty($formedArrayTask['closedBy']) ? "<span style='color: brown;'>Открыта</span>" : "<span style='color: white; background-color: green;'>Закрыта</span>";
    		    if ($taskStatus == "<span style='color: brown;'>Открыта</span>") continue;
 		        echo "<strong>".$formedArrayTask['title']."</strong> Ответственный: ".$formedArrayTask['responsible']['name']." Проект: ".$formedArrayTask['group']['name']." Дата создания: ".$taskDate." Статус задачи: ".$taskStatus."<br><br>";
			}
		}
		?>
	</div>
</body>
</html>