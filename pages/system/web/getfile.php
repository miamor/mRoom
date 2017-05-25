<?php
$W->file = isset($_POST['dir']) ? $_POST['dir'] : null;
echo $W->getCodeContent();
