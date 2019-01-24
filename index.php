<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Emotional IDs</title>
  
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
  <link rel="stylesheet" href="css/main.css?v=1.0">

</head>
<body>
	
	<?php
		
		$jsons = scandir( __DIR__ . '/cache' );
		foreach($jsons as $json) {
			
			if( $json[0] == '.' ) continue;
			
			$datas = json_decode( file_get_contents( __DIR__ . '/cache/' . $json  ) );
			
			$filename = $datas->filename;
			$details = $datas->datas[0];
			
			$emotions = [];
			foreach($details->Emotions as $emotion) {
				$emotions[$emotion->Type] = $emotion->Confidence;
			}
			
			arsort($emotions);
						
			?>
			<div class="card">
				<img src="optimized/<?php echo $filename ?>" />
				<div class="details">
					
					<h3>Gender</h3>
					<p> <?php echo $details->Gender->Value ?> (<?php echo round($details->Gender->Confidence) ?>%) </p>
					
					<h3>Age range</h3>
					<p> <?php echo $details->AgeRange->Low ?>/<?php echo $details->AgeRange->High ?> </p>
					
					<ul>
						<?php foreach($emotions as $type => $emotion) : ?>
						<li><strong><?php echo $type ?></strong>: <?php echo round($emotion) ?>%</li>
						<? endforeach; ?>
					</ul>
					
				</div>
			</div>
			<?php
		}
			
	?>	
	
</body>
</html>