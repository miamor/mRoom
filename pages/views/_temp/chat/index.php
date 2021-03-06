	<link rel="stylesheet" type="text/css" href="<?php echo PLUGINS ?>/mchat/zzchat.css" />
	<?php if ($tmID != 0) echo '<script>var MAIN_URL = "'.MAIN_URL.'"; var teamID = '.$tmID.'; var roomID = '.$tID.'; var meName = "'.$config->me['name'].'"; var cURL = "'.MAIN_URL.'/chat?p='.$params.'&do=update";</script>';
	else echo '<script>var MAIN_URL = "'.MAIN_URL.'"; var teamID = null; var roomID = null; var meName = "'.$config->me['name'].'"; var cURL = "'.MAIN_URL.'/chat?do=update";</script>'; ?>

	<div id="chatbox-forumvi">

		<div id="chatbox-main">

		<div id="chatbox-header">
			<div id="chatbox-me">
				<h2>...</h2>
				<div id="chatbox-action-logout"></div>
				<div class="chatbox-action-checkbox autologin">
					<input type="checkbox" id="chatbox-input-autologin" name="autologin" checked />
					<label for="chatbox-input-autologin">Tự đăng nhập</label>
				</div>
				<div id="chatbox-hidetab" class="show"></div>
			</div>
			<div id="chatbox-title" data-id="publish">
				<h2>Kênh chung</h2>
				<div class="chatbox-action-group edit"></div>
				<div class="chatbox-action-group add"></div>
				<div class="chatbox-action-group close chatbox-action" data-action="/out"></div>
				<div class="chatbox-action-checkbox refresh">
					<input type="checkbox" id="chatbox-input-autorefesh" name="autorefesh" checked />
					<label for="chatbox-input-autorefesh">Tự cập nhật</label>
				</div>
			</div>
		</div>


			<div id="chatbox-wrap">
				<div class="chatbox-content" data-id="publish">
				</div>
			</div>
			<div id="chatbox-messenger-form">
				<form id="chatbox-form" data-key="">
					<input type="hidden" name="sbold" id="chatbox-input-bold" value="0" />
					<input type="hidden" name="sitalic" id="chatbox-input-italic" value="0" />
					<input type="hidden" name="sunderline" id="chatbox-input-underline" value="0" />
					<input type="hidden" name="sstrike" id="chatbox-input-strike" value="0" />
					<input type="hidden" name="scolor" id="chatbox-input-color" value="333333" />
					<input type="hidden" name="team" value="333333" />
					<div id="chatbox-messenger">
						<input type="text" name="message" id="chatbox-messenger-input" placeholder="Type your message..." data-id="publish" maxlength="1024" autocomplete="off" />
					</div>
					<div id="chatbox-option">
						<div id="chatbox-option-bold">B</div>
						<div id="chatbox-option-italic">I</div>
						<div id="chatbox-option-underline">U</div>
						<div id="chatbox-option-strike">S</div>
						<div id="chatbox-option-color" style="background: #333333;"></div>
						<div id="chatbox-option-smiley"></div>
						<div id="chatbox-option-buzz">BUZZ</div>
						<div id="chatbox-option-submit">
							<input type="submit" value="Gửi tin" id="chatbox-submit" />
						</div>
					</div>
				</form>
			</div>
		</div>

		<div id="chatbox-tabs">
			<div class="chatbox-scroll">
				<div id="chatbox-list">
					<div class="chatbox-change active" data-id="publish">
						<h3>Kênh chung</h3>
						<span class="chatbox-change-mess" data-mess="0"></span>
					</div>
				</div>
				<div id="chatbox-members"></div>
			</div>
			<div id="chatbox-copyright">© 2014 - IFC. miamor</div>
		</div>

	</div>

	<audio id="chatbox-buzz-audio">
		<source src="<?php echo PLUGINS ?>/mchat/sound/buzz.ogg" type="audio/ogg" />
		<source src="<?php echo PLUGINS ?>/mchat/sound/buzz.mp3" type="audio/mpeg" />
	</audio>
	<audio id="chatbox-new-audio">
		<source src="<?php echo PLUGINS ?>/mchat/sound/new.mp3" type="audio/mpeg" />
	</audio>

	<script type="text/javascript" src="<?php echo PLUGINS ?>/mchat/zzchat.js"></script>
