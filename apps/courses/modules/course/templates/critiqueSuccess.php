<script language='javascript' src='/js/FusionCharts.js'></script>
<?php echo $submenu ?>
<div id="content">
	<h3>Course Critique for <?php echo $courseObj->getId()?> (<?php echo $courseObj->getDescr()?>)<br/><?php echo $year?> Edition</h3>

	<table class="layout">
		<tr>
			<td>
				<b>Instructors: 
				<?php $len = count($instructorArr)?>
				<?php for ($i=0; $i<$len; $i++):?>
				<?php if ($currInstructor->getId() != $instructorArr[$i]->getId()):?>
				<?php echo link_to($instructorArr[$i]->getLastName().", ".$instructorArr[$i]->getFirstName(), "course/critique?id=".$sf_request->getParameter("id")."&year=".$sf_request->getParameter("year")."&instructor=".$instructorArr[$i]->getId())?>
				<?php else:?>
				<?php echo $instructorArr[$i]->getLastName()?>, <?php echo $instructorArr[$i]->getFirstName()?>
				<?php endif;?>
				<?php if ($i<$len-1):?>| <?php endif;?>
				<?php endfor;?>
				</b>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<h4>Overall Report for <?php echo $currInstructor->getLastName()?>, <?php echo $currInstructor->getFirstName()?></h4>
			</td>
		</tr>
		<tr>
			<td>
			<table class="disptable">
				<tr>
					<th width="150">Number Enrolled</th>
					<th width="150">Number Responded</th>
					<th width="150">Aggregated Rating<a class="help" style="float: right">
					<span>The aggregated rating is the arithmetic average of the mean ratings of all questions below.</span></a></th>
				</tr>
				<tr>
					<td><?php if (isset($numberEnrolled)) echo $numberEnrolled; else echo "?";?></td>
					<td><?php if (isset($numberResponded)) echo $numberResponded; else echo "?";?></td>
					<td><?php echo $aggregatedRating ?></td>
				</tr>
			</table>
			</td>
		</tr>
		<?php $counter=0 ?>
		<?php foreach ($dataArr as $arr):?>
		<?php $counter++?>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><h4><?php echo $counter?>. <?php echo $arr["type"]?>, <?php echo $arr["field"]?></h4></td>
		</tr>
		<tr>
			<td>
				<table class="disptable">
					<tr>
						<?php if ($arr["typeObj"]->getId() == EnumItemPeer::RATING_BOOLEAN):?>
							<th width="60">N/R</th>
							<th width="60">True</th>
							<th width="60">False</th>
						<?php elseif ($arr["typeObj"]->getParentId() == EnumItemPeer::RATING_SCALE):?>
							<th width="60">N/R</th>
							<?php for ($i=1; $i<$arr["typeObj"]->getDescr(); $i++):?>
							<th width="60"><?php echo $i?></th>
							<?php endfor;?>
							<th width="60">Mean</th>
							<th width="60">Median</th>
						<?php endif;?>
					</tr>
					<tr>
						<?php if ($arr["typeObj"]->getId() == EnumItemPeer::RATING_BOOLEAN):?>
							<td><?php echo $arr[0]?></td>
							<td><?php echo $arr[1]?></td>
							<td><?php echo $arr[2]?></td>
						<?php elseif ($arr["typeObj"]->getParentId() == EnumItemPeer::RATING_SCALE):?>
							<td><?php echo $arr[0]?></td>
							<?php for ($i=1; $i<$arr["typeObj"]->getDescr(); $i++):?>
							<td><?php echo $arr[$i]?></td>
							<?php endfor;?>
							<td><?php echo $arr["mean"]?></td>
							<td><?php echo $arr["median"]?></td>
						<?php endif;?>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td><?php $arr["chart"]->renderChart()?></td>
		</tr>
		<?php endforeach;?>
	</table>
</div>
<img class='hidden' src='/images/help.on.gif' />