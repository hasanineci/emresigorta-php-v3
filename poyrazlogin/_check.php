<?php require __DIR__."/../includes/db.php"; foreach(getAllCampaigns() as $c) echo $c["id"].": ".$c["bg_color"].PHP_EOL;
