<?php echo $submenu ?>
<div id="content">
	<h3><?php echo $courseObj->getId()?> (<?php echo $courseObj->getDescr()?>)</h3>
	<?php if (isset($courseDetail)):?>
	<table class="description">
		<tr>
			<td class="title">Description</td>
			<td>
				<?php echo base64_decode($courseDetail->getDetailDescr())?>
			</td>
		</tr>
		<tr>
			<td>First Offered On</td>
			<td><?php echo $courseDetail->getFirstOffered()?></td>
		</tr>
		<tr>
			<td>Last Offered On</td>
			<td><?php echo $courseDetail->getLastOffered()?></td>
		</tr>
	</table>
	<?php endif;?>
	<?php if (isset($currentInstructorList)):?>
	<table class="description">
		<tr>
			<td class="title">Instructor(s)</td>
			<td>
				<?php foreach ($currentInstructorList as $result):?>
				<?php echo link_to($result["name"], "search/searchByInstructor?instructor=".$result["ID"], "title='Click to find all courses taught by this instructor'")?><br/>
				<?php endforeach;?>
			</td>
		</tr>
		<tr>
			<td>Past Instructors</td>
			<td>
				<?php foreach ($pastInstructorList as $result):?>
				<?php if (!in_array($result, $currentInstructorList)):?>
				<?php echo link_to($result["name"], "search/searchByInstructor?instructor=".$result["ID"], "title='Click to find all courses taught by this instructor'")?><br/>
				<?php endif;?>
				<?php endforeach;?>
			</td>
		</tr>
	</table>
	<?php endif;?>
	<?php if (isset($programList)):?>
	<table class="description">
		<tr>
			<td class="title">Related Programs</td>
			<td>
				<?php foreach ($programList as $program):?>
				<?php echo link_to($program["programName"]." (".$program["year"].")", "search/searchByProgram?program=".$program["disciplineId"]."&year=".$program["numYear"],
				 "title='Click to find all courses related to this program'")?><br/>
				<?php endforeach;?>
			</td>
		</tr>
	</table>
	<?php endif;?>
</div>