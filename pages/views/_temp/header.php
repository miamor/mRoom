<?php //echo date("Y-m-d H:i:s");
$config->addJS('plugins', 'bootstrapValidator/bootstrapValidator.min.js');
$config->addJS('plugins', 'sceditor/minified/jquery.sceditor.min.js');
$config->addJS('dist', 'main.js'); ?>
<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo IMG ?>/favicon.ico" />

	<title><?php echo $page_title ?></title>

	<!-- Bootstrap -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="<?php echo MAIN_URL ?>/assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo CSS ?>/font.min.css">
	<!--<link rel="stylesheet" href="<?php echo CSS ?>/style.min.old.css">-->
	<link rel="stylesheet" href="<?php echo CSS ?>/plugins.css">
	<!-- Page style CSS -->
	<link rel="stylesheet" href="<?php echo CSS ?>/light.min.css">

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="<?php echo MAIN_URL ?>/assets/jquery/jquery-2.2.3.min.js"></script>

	<!-- Latest compiled and minified JavaScript -->
	<script src="<?php echo MAIN_URL ?>/assets/bootstrap/js/bootstrap.min.js"></script>
	<script>var MAIN_URL = '<?php echo MAIN_URL ?>' </script>

</head>
<body>

	<nav id="top_navbar" class="navbar navbar-static-top">
		<div class="col-lg-1 no-padding"></div>
		
		<div class="top-left col-lg-2 no-padding-left">
			<div class="logo">
				<a href="<?php echo MAIN_URL ?>"><span class="fa fa-code"></span> mRoom</a>
			</div>
		</div> <!-- .left-top -->
		
		<div class="top-middle col-lg-4 no-padding">
			<ul class="items-list">
				<li class="one-item <?php if ($page == 'home') echo 'active' ?>" id="home"><a href="<?php echo MAIN_URL ?>">Home</a></li>
				<li class="one-item dropdown <?php if ($page == 'web' || $page == 'problems' || $page == 'hack') echo 'active' ?>" id="code">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Code</a>
					<ul class="dropdown-menu with-triangle primary pull-left">
						<li class="left-menu-one <?php if ($page == 'problems') echo 'active' ?>"><a href="<?php echo MAIN_URL ?>/p">Algorithm/Problem</a></li>
						<li class="left-menu-one <?php if ($page == 'web') echo 'active' ?>"><a href="<?php echo MAIN_URL ?>/w">Web programming</a></li>
						<li class="left-menu-one hide <?php if ($page == 'hack') echo 'active' ?>"><a href="<?php echo MAIN_URL ?>/h">Hacking</a></li>
					</ul>
				</li>
				<li class="one-item <?php if ($page == 'contest') echo 'active' ?>" id="contest"><a href="<?php echo MAIN_URL ?>/c">Contest</a></li>
				<li class="one-item <?php if ($page == 'forum') echo 'active' ?>" id="contest"><a href="<?php echo MAIN_URL ?>/b">Forum</a></li>
			</ul>
		</div>

		<div class="top-right col-lg-3 no-padding-right">
			<div class="user-right-bar left">
				<ul class="nav-users">
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<img src="<?php echo ($config->u) ? $config->me['avatar'] : MAIN_URL.'/data/img/anonymous.jpeg' ?>" class="avatar img-circle">
							<strong class="s-title"><?php echo ($config->u) ? $config->me['name'] : '[Guests]' ?></strong>
							<?php if ($config->u) echo '<span class="hidden myID" id="'.$config->me['username'].'"></span>' ?>
							<div class="u-rank">
								<span>#</span>
								<strong><?php echo $config->me['rank'] ?></strong>
							</div>
						</a>
						<ul class="dropdown-menu with-triangle pull-right">
						<?php if ($config->u) { ?>
							<li class="user-header">
								<img src="<?php echo $config->me['avatar'] ?>" class="img-circle" alt="User Image">
								<p>
									<?php echo $config->me['name'].' - <small>@'.$config->me['username'].'</small>' ?>
								</p>
							</li>
							<!-- Menu Body -->
							<li class="user-body u-sta sta-list">
								<div class="sta-one u-submissions">
									<strong><?php echo $config->me['submissions'] ?></strong>
									submits
								</div>
								<div class="sta-one u-score">
									<strong><?php echo $config->me['score'] ?></strong>
									avarage
								</div>
								<div class="sta-one u-submissions">
									<strong><?php echo $config->me['AC'] ?><span>/<?php echo $config->me['total_tests'] ?></span></strong>
									AC
								</div>
							</li>
							<!-- Menu Footer-->
							<li class="user-footer">
								<div class="pull-left">
									<a class="btn btn-default btn-flat" href="<?php echo $config->me['link'] ?>">Profile</a>
									<span class="member-type gensmall" style="margin-left:10px"><?php echo ($config->me['is_mod'] == 1) ? 'MOD' : 'MEMBER' ?></span>
								</div>
								<div class="pull-right">
									<a class="btn btn-default btn-flat" href="<?php echo MAIN_URL ?>/logout">Logout</a>
								</div>
							</li>
						<?php } ?>
						</ul>
				<!--	<ul class="dropdown-menu with-triangle primary pull-right">
						<?php if ($config->u) { ?>
							<li><a href="<?php echo $config->me['link'] ?>">Me</a></li>
							<li><a href="<?php echo $config->me['link'] ?>/submissions">My submissions</a></li>
							<li><a href="<?php echo $config->me['link'] ?>/settings">Settings</a></li>
							<li><a href="<?php echo $config->me['link'] ?>/teams">Teams</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo MAIN_PATH ?>/logout">Logout</a></li>
						<?php } else echo '<li><a href="'.MAIN_PATH.'/login">Login</a></li>'; ?>
						</ul> -->
					</li>
				</ul>
			</div>


<?php /*if ($config->u) { ?>
			<div class="noti-right-bar left">
				<ul class="nav-users">
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span class="badge badge-primary icon-count">2</span>
							<i class="fa fa-globe"></i>
						</a>
						<ul class="dropdown-menu with-triangle pull-right">
							<li>
								<div class="nav-dropdown-heading">Notifications</div>
								<div class="nav-dropdown-content scroll-nav-dropdown">
									<ul class="notification-load">
										
									</ul>
								</div>
								<div class="btn btn-primary btn-block">See all notifications</div>
							</li>
						</ul>
					</li>
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-inbox"></i>
						</a>
						<ul class="dropdown-menu with-triangle pull-right">
							<li>
								<div class="nav-dropdown-heading">
									Messages
								</div>
								<div class="nav-dropdown-content scroll-nav-dropdown">
									<ul class="notification-load">
									</ul>
								</div>
								<div class="btn btn-primary btn-block" href="http://localhost/astro/inbox">See all messages</div>
							</li>
						</ul>
					</li>
				</ul>
			</div>
<?php } */ ?>

		</div> <!-- .left-bottom -->
		
	</nav>

	<div class="navbox">
		<div class="col-lg-9 no-padding pathname-box">
			<a class="bread_nav" href="<?php echo MAIN_URL ?>"><span>mRoom</span></a>
		<?php if ($__pageAr[0] == 'p') {
				echo '<a class="bread_nav" href="'.$config->pLink.'"><span>Problems</span></a>'; 
				if ($__pageAr[1]) echo '<a class="bread_nav" href="'.$problem->link.'"><span>'.$problem->title.'</span></a>';
				if ($__pageAr[2] == 'submissions') echo '<a class="bread_nav" href="'.$problem->link.'/submissions"><span>Submissions</span></a>';
			}
			else if ($__pageAr[0] == 'c') {
				echo '<a class="bread_nav" href="'.$config->cLink.'"><span>Contests</span></a>'; 
				if ($__pageAr[1]) echo '<a class="bread_nav" href="'.$contest->link.'"><span>'.$contest->title.'</span></a>';
				if ($__pageAr[2] == 'discussions') echo '<a class="bread_nav" href="'.$problem->link.'/discussions"><span>Discussions</span></a>';
				else if ($__pageAr[2]) echo '<a class="bread_nav" href="'.$contest->link.'/'.$__pageAr[2].'"><span>'.$problem->title.'</span></a>';
			}
			else if ($__pageAr[0] == 'b') {
				echo '<a class="bread_nav" href="'.$config->bLink.'"><span>Forum</span></a>'; 
				if ($__pageAr[1]) echo '<a class="bread_nav" href="'.$forum->link.'"><span>'.$forum->title.'</span></a>';
				if ($__pageAr[2]) echo '<a class="bread_nav" href="'.$topic->link.'"><span>'.$topic->title.'</span></a>';
			}
			else if ($__pageAr[0] == 'u') echo '<a class="bread_nav" href="'.$config->uLink.'"><span>Users</span></a>'; 
			else if ($__pageAr[0] == 'w') echo '<a class="bread_nav" href="'.$config->cLink.'"><span>Web programming</span></a>';
			if ($page_mode) echo '<a class="bread_nav" href="?mode='.$page_mode.'"><span>'.mb_ucfirst($page_mode, 'utf8').'</span></a>';
		?>
		</div>
		<div class="form-search col-lg-3">
			<form class="search-form" action="<?php echo MAIN_URL ?>/search">
				<input name="keyword" class="search-input" placeholder="Input something..." type="text">
				<div id="search_button" class="search-button"></div>
			</form>
		</div>
		<div class="clearfix"></div>
	</div>

	<div id="main-content" class="page-<?php echo $page ?>">
