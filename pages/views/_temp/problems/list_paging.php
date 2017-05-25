<?php
// the page where this paging is used
$pageNum_dom = $config->pLink;

echo '<ul class="pagination">
<li><a>Go to page</a></li>';

// button for first page
if ($pageNum > 1) {
    echo "<li><a href='{$pageNum_dom}' title='Go to the first page.'>";
        echo '<span class="fa fa-angle-double-left"></span>';
    echo "</a></li>";
}

// count all products in the database to calculate total pages
$total_rows = $problem->countAll();
$total_pages = ceil($total_rows / $records_per_page);

// range of links to show
$range = 2;

// display links to 'range of pages' around 'current page'
$initial_num = $pageNum - $range;
$condition_limit_num = ($pageNum + $range)  + 1;

for ($x=$initial_num; $x<$condition_limit_num; $x++) {

    // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
    if (($x > 0) && ($x <= $total_pages)) {

        // current page
        if ($x == $pageNum) {
            echo "<li class='active'><a href=\"#\">$x <span class=\"sr-only\">(current)</span></a></li>";
        } 

        // not current page
        else {
            echo "<li><a href='{$pageNum_dom}?page=$x'>$x</a></li>";
        }
    }
}

// button for last page
if($pageNum<$total_pages){
    echo "<li><a href='" .$pageNum_dom . "?page={$total_pages}' title='Last page is {$total_pages}.'>";
        echo '<span class="fa fa-angle-double-right"></span>';
    echo "</a></li>";
}

echo '</ul>
<div class="clearfix"></div>';
?>
