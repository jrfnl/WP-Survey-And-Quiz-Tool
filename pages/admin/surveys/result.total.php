<div class="wrap">

	<div id="icon-tools" class="icon32"></div>
	<h2>WP Survey And Quiz Tool - Survey Results</h2>

	<?php require WPSQT_DIR.'pages/admin/misc/navbar.php'; ?>

	<?php if ( $sections == false ) { ?>

		<p>There are no results for this survey yet.</p>

	<?php } else { ?>

		<?php foreach ( $sections as $sectionKey => $secton ){
				foreach ( $secton['questions'] as $questonKey => $question ) {
			?>
				<h3><?php echo $question['name']; ?></h3>

				<?php if ( $question['type'] == "Multiple Choice" ||
						   $question['type'] == "Dropdown" ) {
							$googleChartUrl = 'http://chart.apis.google.com/chart?chs=400x185&cht=p';
							$valueArray    = array();
							$nameArray     = array();
						   foreach ( $question['answers'] as $answer ) {
						   		$nameArray[] = $answer['text'];
								$valueArray[] = $answer['count'];
						   }

							$googleChartUrl .= '&chd=t:'.implode(',', $valueArray);
							$googleChartUrl .= '&chl='.implode('|',$nameArray);
							?>

							<img src="<?php echo $googleChartUrl; ?>" alt="<?php echo $question['name']; ?>" />
					<?php } else if ($question['type'] == "Free Text") {

							$i = 1; // Variable used to count answers - used later

							?> <em>All answers for this question</em> <?php

							foreach($uncachedresults as $uresult) {
								$usection = unserialize($uresult['sections']);

								foreach($usection as $result) {

									foreach($result['answers'] as $uanswerkey => $uanswer) {
										if($uanswerkey == $questonKey && in_array($uanswerkey, $freetextq)) {
											echo '<p>'.$i.') '.$uanswer['given'][0].'</p>';
											$i++;
										}

									}
								}

							}
						  } else if ($question['type'] == "Likert") {
								$googleChartUrl = 'http://chart.apis.google.com/chart?&cht=bvs';
								$valueArray    = array();
								$nameArray     = array();
								$maxValue = 0;
								$numAnswers = count($question['answers']);
								
								// Populates data array
								foreach ( $question['answers'] as $key => $answer ) {
									$nameArray[] = $key;
									$valueArray[] = $answer['count'];
									// Gets the maximum value
									if ($answer['count'] > $maxValue)
										$maxValue = $answer['count'];
								}
								// Makes chart wider if its an agree/disagree question
								if (array_key_exists('disagree', $question['answers'])) {
									$googleChartUrl .= '&chs=500x250&chbh=20,70,10';
								} else {
									$googleChartUrl .= '&chs=350x250';
								}
								$googleChartUrl .= '&chm=N,000000,0,,10|N,000000,1,,10|N,000000,2,,10'; // Adds the count above bars
								$googleChartUrl .= '&chds=0,'.(++$maxValue); // Sets scaling to a little bit more than max value
								$googleChartUrl .= '&chxt=x&chxl=0:|'.implode('|', $nameArray); // Sets labelling to x-axis only
								$googleChartUrl .= '&chd=t:'.implode(',', $valueArray); // Chart data
								?><img src="<?php echo $googleChartUrl; ?>" alt="<?php echo $question['name']; ?>" /><?php
						  } else {
								echo 'Something went really wrong, please report this bug to the forum. Here\'s a var dump which might make you feel better.<pre>'; var_dump($question); echo '</pre>';
						  } ?>

		<?php }
		}
	} ?>

</div>
<?php require_once WPSQT_DIR.'/pages/admin/shared/image.php'; ?>