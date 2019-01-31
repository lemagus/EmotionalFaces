<?php

	function mixColor( $emotions, $threshold = 5 ) {
		
		$colors = [
			'CALM' 		=>  [0,1,1],
			'DISGUSTED'	=>  [0,1,0],
			'ANGRY'		=>  [1,0,0],
			'HAPPY'		=>  [1,1,0],
			'SAD' 		=>  [0,0,1], 
			'SURPRISED' =>  [1,1,1],
			'CONFUSED'	=>  [1,0,1]
		];
		
		$red = [];
		$green = [];
		$blue = [];
				
		foreach($emotions as $emotion => $value) {
			if($value < $threshold) continue;
			
			$colorMatrix = $colors[$emotion];
			foreach($colorMatrix as $pos => $exists){
				
				switch(true){
					case $pos == 0 && $exists == 1 :
						$red[] = $value;
						break;
					case $pos == 1 && $exists == 1 :
						$green[] = $value;
						break;
					case $pos == 2 && $exists == 1 :
						$blue[] = $value;
						break;
				}	
			}
		}
		
		$red =  count($red) > 0 ? round(array_sum($red) / count($red)) : 0;		
		$green = count($green) > 0 ? round(array_sum($green) / count($green)) : 0;		
		$blue = count($blue) > 0 ? round(array_sum($blue) / count($blue)) : 0;
		
		$red = $red > 0 ?  round(($red/100) * 255) : 0;
		$green = $green > 0 ?round(($green/100) * 255) : 0;
		$blue = $blue > 0 ? round(($blue/100) * 255) : 0;
		
		return 'rgb('.$red.','.$green.','.$blue.')';
				
	}
	
	
	
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Emotional IDs</title>
  
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
  <link rel="stylesheet" href="css/main.css?v=1.0">
  <script src="https://unpkg.com/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"></script>

</head>
<body >
	<div class="wrapper">
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
			<div class="card"  >
				
				<div class="picture">
					<div class="color" style="background-color: <?php echo mixColor($emotions) ?>" ></div>
					<div class="img" style="background-image: url('optimized/<?php echo $filename ?>')" ></div>
				</div>
				
				<div class="details" style="background-color: <?php echo mixColor($emotions) ?>" >
					
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
	</div>
</body>
</html>