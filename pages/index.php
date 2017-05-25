<?php
if (!$do && !$v && !$temp) include 'pages/views/_temp/header.php';
// include object files
include_once 'objects/user.php';
include_once 'objects/team.php';
include_once 'objects/topic.php';
$user = new User();
$team = new Team();
$topic = new Topic();

$topUsers = $user->readAll(8);
$topTeams = $team->readAll(8);

$lastTopic = $topic->readAll(true, '', '', '', 8);
$activeTopic = $topic->readAll(true, '', '', 'replies DESC', 8);
$viewsTopic = $topic->readAll(true, '', '', 'views DESC', 8);

echo '<div class="col-lg-9">';
include 'views/_temp/'.$page.'/noti.php';
include 'views/_temp/forum/statistics.php';
echo '</div>';

echo '<div class="col-lg-3">';
include 'views/_temp/'.$page.'/topUsers.php';
include 'views/_temp/'.$page.'/topTeams.php';
echo '</div>';

echo '<div class="clearfix"></div>';