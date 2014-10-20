
	<?php 

		if (!empty($_REQUEST["paged"]))
			$paged = $_REQUEST["paged"];
		else
			$paged = 1;
	?>

	<div class="records_found"><?php echo $records->found_posts?> records found</div>

	<?php if($records->found_posts):?>

	<ul class="pager">
		<li>
			<a href="#" class="pagination_link" rel="<?php echo $paged - 1?>">&larr; Previous</a>
		</li>
		<li>
			<a href="#" class="pagination_link" rel="<?php echo $paged + 1?>">Next &rarr;</a>
		</li>
	</ul>

	<div class="navigation">
		Page <?php echo $paged?> of <?php echo $records->max_num_pages?>
	</div>

	<br>

	<table border="0" cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<?php foreach ($records_header as $record):?>
					<th><?php echo $record?></th>
				<?php endforeach;?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($records_data as $data):?>
			<tr>
				<?php foreach ($data as $field):?>
				<td><?php echo $field?></td>
				<?php endforeach;?>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>

	<div class="navigation">
		Page <?php echo $paged?> of <?php echo $records->max_num_pages?>
	</div>

	<ul class="pager">
		<li>
			<a href="#" class="pagination_link" rel="<?php echo $paged - 1?>">&larr; Previous</a>
		</li>
		<li>
			<a href="#" class="pagination_link" rel="<?php echo $paged + 1?>">Next &rarr;</a>
		</li>
	</ul>

	<?php endif;?>