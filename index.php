<?php
require_once './db/Model.php';
require_once './logic/Divider.php';

$model = new Model();
$row = $model->getFirst();

if ($row) {
    $divider = new Divider($row['html']);
    $columns = $divider->divide();
}
?>

<div>
    <div style="width: 45%; float: left;">

        <?php
        if (isset($columns[0])) {
            echo $columns[0];
        }
        ?>

    </div>
    <div style="width: 45%; float: right;">

        <?php
        if (isset($columns[1])) {
            echo $columns[1];
        }
        ?>

    </div>
</div>
