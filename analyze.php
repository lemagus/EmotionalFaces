<?php
	
	include __DIR__ . '/_config.php';
	include __DIR__ . '/vendor/autoload.php';
	
	use Aws\Rekognition\RekognitionClient;
	
	$rekognition = new RekognitionClient([
  		'region'            => 'eu-west-1',
  		'version'           => '2016-06-27',
  		'credentials' => [
       		'key'    => API_KEY,
	   		'secret' => API_SECRET,
		]
	]);
	
	$pictures = scandir( __DIR__ . '/optimized' );
	if(!is_dir(__DIR__ . '/cache')) mkdir(__DIR__ . '/cache', 0777);
	
	foreach($pictures as $picture) {
		if( $picture[0] == '.' ) continue;
		
		$filepath = __DIR__ . '/optimized/' . $picture;
		
		$result = $rekognition->detectFaces([
		  'Attributes' => [ "ALL" ],
	      'Image' => array(
	         'Bytes' => file_get_contents($filepath),
			)
		]);
		
		$datas = [
			'filename'	=> $picture,
			'datas'	=> $result["FaceDetails"]
		];		
		$datas = json_encode($datas, JSON_PRETTY_PRINT);
		
		file_put_contents(__DIR__ . '/cache/' . basename($picture, '.jpg') . '.json', $datas);
		
		echo "Saved picture " . $picture;
		echo "\n";
		
	}