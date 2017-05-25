	<section class="col-lg-8 no-padding">
		<div class="p-detail">
			<div class="p-content">
				<?php echo $problem->content ?>
			</div>
			<div class="p-samples">
				<h4>Samples</h4>
				<div class="p-input col-lg-6">
					<div class="labels">Input</div>
					<pre style="margin-top: 5px;" id="input_<?php echo $problem->iid ?>"><?php echo $problem->input ?></pre>
				</div>
				<div class="p-output col-lg-6">
					<div class="labels">Output</div>
					<pre style="margin-top: 5px;" id="output_<?php echo $problem->iid ?>"><?php echo $problem->output ?></pre>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		
		<div class="p-author right">
			<a class="left" data-online="<?php echo $problem->author['online'] ?>" href="<?php echo $problem->author['link'] ?>"><img src="<?php echo $problem->author['avatar'] ?>" class="left p-author-img"/></a>
			<div class="p-author-name"><a href="<?php echo $problem->author['link'] ?>"><?php echo $problem->author['name'] ?></a></div>
			<div class="gensmall p-author-rank">Ranking: <?php echo $problem->author['rank'] ?></div>
		</div>
		
<!--		<div class="problem-sta p-sta">
			<div class="problem-view" title="Views"><i class="fa fa-eye"></i> <?php echo $problem->views ?></div>
			<div class="problem-submission" title="Submissions"><span class="left"><i class="fa fa-list"></i> <?php echo $problem->submissions ?></span>
				<div class="problem-submission-details right" title="Submissions details">
					<div class="s-bar s-bar-error label-danger" style="width:<?php echo $problem->WAper .'%' ?>" title="Total WA or errors"></div>
					<div class="s-bar s-bar-success label-success" style="width:<?php echo $problem->ACper .'%' ?>" title="<?php echo $problem->totalAC ?> AC of all <?php echo $problem->totalTests ?>"></div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div> -->
	</section>
	
	<section class="col-lg-4 no-padding-right">
		<div class="p-info">
			<h4 class="p-info-tit">Problem Info</h4>
			<div class="p-info-content">
				<div class="p-info-txt">
					<p style="margin-left: 5px">Score type: <strong title="It's either 'Accepted' or 'Not accepted'."><?php echo $problem->score ?></strong></p>
					<p style="margin-left: 5px">Time limit: <strong><?php echo $problem->time_limit ?>s</strong></p>
					<p style="margin-left: 5px">Memory Limit: <strong><?php echo $problem->memory_limit ?></strong></p>
					<p style="margin-left: 5px">Input: <strong><?php echo $problem->inputType ?></strong></p>
					<p style="margin-left: 5px">Output: <strong><?php echo $problem->outputType ?></strong></p>
				</div>
			</div>
		</div>
<!--		<div class="p-rating chart-ratings">
			<div class="chart-grade rate-grade" title="<?php echo $problem->ratings ?>"><?php echo $problem->ratings ?></div>
			<div class="chart-star star-info">
				<span class="fa fa-star"></span>
				<span class="fa fa-star"></span>
				<span class="fa fa-star"></span>
				<span class="fa fa-star"></span>
				<span class="fa fa-star-o"></span>
				<div class="gensmall rl-review-count">(<b>1</b>) <a>ratings</a></div>
			</div>
		</div> -->
	</section>
	<div class="clearfix"></div>
