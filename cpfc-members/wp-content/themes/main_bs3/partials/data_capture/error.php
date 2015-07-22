<?php
$templateData = \CPFCMembers\TemplateData::getInstance();
$errorType = $templateData->get('error_type'); ?>

<section class="datacapture-panel">

<h1><?php the_field("data_capture_error_{$errorType}_heading"); ?></h1>

<?php the_field("data_capture_error_{$errorType}_copy"); ?>

</section>