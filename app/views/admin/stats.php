<?php

function print_pie_chart($source, $round = 0.01) {
	$total = array_sum($source);
	$labels = array();
	$values = array();
	foreach ($source as $k => $v) {
		$check = $v / $total;
		if ($check > $round) {
			$labels[] = $k;
			$values[] = $v / $total;
		}
	}

	$defect = 1 - array_sum($values);
	if ($defect > $round) {
		$labels[] = 'Others';
		$values[] = $defect;
	}

	$labels = implode('|', $labels);
	$values = implode(',', $values);

	$gchart_q = array('cht' => 'p3', 'chs' => '640x400', 'chdl' => $labels, 'chd' => 't:' . $values);

	$query_string = http_build_query($gchart_q);
	$query_string = htmlentities($query_string);
	$chart_url = 'http://chart.googleapis.com/chart?' . $query_string;
	?><img src="<?php echo $chart_url; ?>" class="chart"><?php
}

function print_leaderboard($source, $label, $custom_total = 0, $custom_total_label = '') {
	// sort
	arsort($source);

	// labels for total
	$total_label = $custom_total_label ? $custom_total_label : 'total';
	$total = $custom_total ? $custom_total : array_sum($source);

	// count distinct keys, but remove empty keys
	$source_dupe = $source;
	unset($source_dupe['']);
	$source_key_count = count($source_dupe);

	?>
	<table>
		<tr>
			<td></td>
			<td><strong><?php echo $source_key_count; ?></strong> <?php echo $label ?></td>
			<td><strong><?php echo $total; ?></strong> <?php echo $total_label ?></td>
		</tr>
	<?php $i = 0; foreach ($source as $n => $s): ?>
		<tr>
			<td class="rank"><?php
			if ($n) {
				if ($prev == $s) {
					++$i;
					echo "<span class=\"repeat\">$prev_rank</span>";
				}
				else
					echo ++$i; 
			} ?></td>
			<td><?php echo $n; if (!$n): ?><i>(unspecified)</i><?php endif; ?></td>
			<td><strong><?php echo $s; ?></strong></td>
		</tr>
	<?php
	if ($prev != $s)
		$prev_rank = $i;
	$prev = $s;
	endforeach;
	?>
	</table>
	<?php
}

$db = Helium::db();

?>
<?php $this->header('Statistics'); ?>

<header class="stage-title">
<h1>Administration</h1>
<h2>Applicant Statistics</h2>
</header>

<div class="container">

<section class="statbox application_stage">
	<?php
	
	$total_apps = intval($db->get_var('SELECT COUNT(*) FROM applicants WHERE 1'));
	$unexpired = intval($db->get_var('SELECT COUNT(*) FROM applicants WHERE expires_on > CURRENT_TIMESTAMP OR (submitted=1)'));
	$anomalies = intval($db->get_var('SELECT COUNT(*) FROM applicants WHERE expires_on < CURRENT_TIMESTAMP AND submitted=0 AND finalized=1'));
	$submitted = intval($db->get_var('SELECT COUNT(*) FROM applicants WHERE submitted=1'));

	$now = new HeliumDateTime;
	$now->setTime(23, 59, 59);
	$w = $now->format('w');
	// we expire sunday
	$w = (int) $w ? $w : 7;
	$int = 7 - $w;
	$now->modify('+' . $int . ' days');
	$upcoming = intval($db->get_var("SELECT COUNT(*) FROM applicants WHERE expires_on='$now' AND submitted=0"));
	$upcoming_finalized = intval($db->get_var("SELECT COUNT(*) FROM applicants WHERE expires_on='$now' AND submitted=0 AND finalized=1"));

	$early_birds = intval($db->get_var("SELECT COUNT(*) FROM applicants WHERE expires_on > '$now' AND submitted=0 AND finalized=1"));

	?>
	<header>Stages</header>

	<table class="tree">
		<tr>
			<td class="active" rowspan="7"><strong><?php echo $total_apps; ?></strong> activated</td>
			<td class="active" rowspan="2"><strong><?php echo $total_apps - $unexpired; ?></strong> expired</td>
			<td class="active"><strong><?php echo $total_apps - $unexpired - $anomalies; ?></strong> normal</td>
			<td>
		</tr>
		<tr>
			<td class="active"><strong><?php echo $anomalies; ?></strong> anomalies</td>
			<td></td>
		</tr>
		<tr>
			<td class="active" rowspan="5"><strong><?php echo $unexpired; ?></strong> unexpired</td>
			<td class="active"><strong><?php echo $submitted; ?></strong> confirmed</td>
			<td></td>
		</tr>
		<tr>
			<td class="active" rowspan="2"><strong><?php echo $upcoming; ?></strong> upcoming</td>
			<td class="active"><strong><?php echo $upcoming_finalized; ?></strong> finalized</td>
		</tr>
		<tr>
			<td class="active"><strong><?php echo $upcoming - $upcoming_finalized; ?></strong> not yet finalized</td>
		</tr>
		<tr>
			<td class="active" rowspan="2"><strong><?php echo $unexpired - $submitted - $upcoming; ?></strong> new activations</td>
			<td class="active"><strong><?php echo $early_birds; ?></strong> early birds</td>
		</tr>
		<tr>
			<td class="active"><strong><?php echo $unexpired - $submitted - $upcoming - $early_birds; ?></strong> pars</td>
		</tr>
	</table>

	<?php print_pie_chart(array(
		'Not yet finalized' => ($upcoming - $upcoming_finalized),
		'Finalized' => ($upcoming_finalized + $early_birds),
		'Confirmed' => $submitted,
		'Expired' => ($total_apps - $unexpired)
	))?>
</section>

<section class="statbox gender_distribution">
	<?php
	
	$guys = intval($db->get_var("SELECT COUNT(*) FROM applicant_details LEFT JOIN applicants ON applicants.id=applicant_details.applicant_id WHERE jenis_kelamin='L' AND (expires_on > CURRENT_TIMESTAMP OR (submitted=1))"));
	$girls = intval($db->get_var("SELECT COUNT(*) FROM applicant_details LEFT JOIN applicants ON applicants.id=applicant_details.applicant_id WHERE jenis_kelamin='P' AND (expires_on > CURRENT_TIMESTAMP OR (submitted=1))"));
	
	?>
	<header>Genders</header>

	<table class="tree">
		<tr>
			<td class="active" rowspan="2"><strong><?php echo $unexpired; ?></strong> unexpired</td>
			<td class="active"><strong><?php echo $girls; ?></strong> female</td>
		</tr>
		<tr>
			<td class="active"><strong><?php echo $guys; ?></strong> male</td>
		</tr>
	</table>

	<?php print_pie_chart(array(
		'Male' => $guys,
		'Female' => $girls
	))?>

</section>

<section class="statbox school_distribution">
	<?php

	function get_school_stats($column) {
		$db = Helium::db();
		$school_names = $db->get_col("SELECT $column FROM applicant_details LEFT JOIN applicants ON applicants.id=applicant_details.applicant_id WHERE expires_on > CURRENT_TIMESTAMP OR (submitted=1)");
		$translate = array();
		$counts = array();
		foreach ($school_names as $school) {
			$school = Gatotkaca::sanitize_school($school);

			$lowercased = strtolower($school);
			if ($translate[$lowercased])
				$clean_name = $translate[$lowercased];
			else
				$clean_name = $translate[$lowercased] = $school;
			if ($counts[$clean_name])
				$counts[$clean_name]++;
			else
				$counts[$clean_name] = 1;
		}
	
		return $counts;
	
	}
	
	$schools = get_school_stats('pendidikan_sma_nama_sekolah');

	arsort($schools);
	?>
	<header>Schools</header>

	<?php print_leaderboard($schools, 'schools', 0, 'unexpired') ?>
	
	<?php $schools_dupe = $schools; unset($schools_dupe['']); print_pie_chart($schools_dupe, 0.05) ?>

</section>


<section class="statbox cities">
	<?php
	
	$addresses = $db->get_col("SELECT alamat_lengkap FROM applicant_details LEFT JOIN applicants ON applicants.id=applicant_details.applicant_id WHERE (expires_on > CURRENT_TIMESTAMP) OR (submitted=1)");
	$cities = array();
	foreach ($addresses as $a) {
		$a = unserialize($a);
		$city = $a['kota'];
		$city = strtolower($city);
		if ($city) {
			$city[0] = strtoupper($city[0]);
			if ($cities[$city])
				$cities[$city]++;
			else
				$cities[$city] = 1;
		}
	}
	arsort($cities);

	?>
	<header>Cities and Towns</header>

	<?php print_leaderboard($cities, 'cities', 0, 'unexpired') ?>

	<?php print_pie_chart($cities); ?>

</section>

<!-- 
<h1>Phone Numbers</h1>

<p>
<?php

$addresses = $db->get_col("SELECT alamat_lengkap FROM applicant_details LEFT JOIN applicants ON applicants.id=applicant_details.applicant_id WHERE submitted=1");
// foreach ($addresses as $a) {
// 	$a = unserialize($a);
// 	$hp = $a['hp'];
// 	if ($hp) echo $hp . ', ';
// }

?>
</p>

<h1>Email Addresses</h1>

<p>

<?php

foreach ($addresses as $a) {
	$a = unserialize($a);
	$email = $a['email'];
	if ($email) echo $email . ', ';
}

?>
</p>
-->
</div>

<?php $this->footer(); ?>