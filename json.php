<!-- Todo:
* Upload to GitHub (somehow)
* Figure out pages automatically
* Optimize more
* Make stuff look nice
* Alphabetize by sponsor
-->

<html>
	<head>
		<title>Wavo.me Stems // James Martindale</title>
	</head>
	<body>
		<?php

		//Note for any people who stumble upon this link
		echo '<i>Please note: this is a testing page I wrote for myself. It works by going through the entirety of Wavo.me hunting for remix stems. Because of this, artist names and download links are not 100% accurate. This page will eventually be taken down, because the purpose of this page was to create a list for me so that I can download everything, tidy them up a bit, and then upload them to <a href="http://jkmartindale.com/stemport" target="_blank">http://jkmartindale.com/stemport</a>. But because of a <a href="https://redd.it/4biluo" target="_blank">certain Reddit post</a>, I\'ll leave this up a little longer than originally planned. And also because I\'m lazy. If for some reason you care, you can <a href="http://eepurl.com/bVGjsz" target="_blank">sign up for email updates</a> about whatever crap I do in the future.</i><br>';

		//Maximum page was determined to be 750, this should be updated in the future.
		for ($index; $index < 800; $index += 10)
		{
			//Download and serialize JSON
			$json = file_get_contents('https://wavo.me/search/playlists/remix?from=' . $index);
			$results = json_decode($json);

			//For each object in the page
			foreach ($results as $result)
			{
				//Check to see if it is an actual remix contest
				if (isset($result->contestInfo))
				{
					//Download contest page and parse download links
					$url = 'https://wavo.me/' . $result->ownerName . '/' . $result->name;
					preg_match_all('/\"downloadPackageUrl\":\"([^\"]*)\",/', file_get_contents($url), $matches);
					$download = end($matches[1]);
					
					//Detect if the download URL is useless and indicate such
					$gone = (empty($download) || preg_match('/^.*\.(?:png|jpg)$/i', $download) !== 0);
					if ($gone)
						echo '<s>';

					//Display contest entry
					echo '<b>', $result->ownerFullName, '</b> ', $result->title, ' <a href="', $url . '" target="_blank">[Contest]</a>';
					
					//Finish 404 warning
					if ($gone)
						echo '</s>';
					
					//Display link to stems if they exist
					if ($result->ownerFullName === 'Years & Years ')
						echo ' <a href="https://d3cmve2ootoy88.cloudfront.net/files/2z5YD8lSvXNTek2KTBfRrJ:0OpvM07gOcr0Ie0578SVjJ" target="_blank">[Stems]</a>';
					else	if (!$gone)
						echo ' <a href="', end($matches[1]), '" target="_blank">[Stems]</a>';
					echo '<br>';
				}
			}
			unset($results);
		}

		?>
	</body>
</html>