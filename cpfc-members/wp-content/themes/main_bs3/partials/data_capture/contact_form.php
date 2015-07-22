<?php
$form = \CPFCMembers\DataCaptureContactForm::getInstance();
$templateData = \CPFCMembers\TemplateData::getInstance(); ?>

<section class="datacapture-panel">
<?php
switch ($templateData->get('status')) {
    case 'SUCCESS': ?>
<h1><?php the_field('data_capture_contact_details_success_heading'); ?></h1>

<?php the_field('data_capture_contact_details_success_copy'); ?>
    <span id="datacapture-success" data-result="true"></span>
    <?php
        break;

    default: ?>
<h1><?php the_field('data_capture_contact_details_heading'); ?></h1>

<?php the_field('data_capture_contact_details_copy'); ?>

<form class="datacapture" method="POST" action="<?php echo \CPFCMembers\Input::uri(); ?>">
    <fieldset>
        <input type="hidden" name="details" value="contact" />
        <?php echo $form->securityField(); ?>

        <?php
        $form->displayError('form'); ?>

        <div class="form-block-wrap form-group <?php echo $form->displayErrorIndicator('mobile_phone'); ?>">
            <label for="mobile" class="control-label">Mobile phone <?php echo $form->indicateRequired('mobile_phone'); ?></label>
            <input class="form-control" type="text" name="mobile_phone" id="mobile" placeholder="Your mobile number" value="<?php echo $form->getValue('mobile_phone'); ?>" />
            <?php
            $form->displayError('mobile_phone'); ?>
        </div>

        <div class="form-block-wrap form-group <?php echo $form->displayErrorIndicator('daytime_phone'); ?>">
            <label for="phone" class="control-label">Daytime phone <?php echo $form->indicateRequired('daytime_phone'); ?></label>
            <input class="form-control" type="text" name="daytime_phone" id="phone" placeholder="Your daytime phone number" value="<?php echo $form->getValue('daytime_phone'); ?>" />
            <?php
            $form->displayError('daytime_phone'); ?>
        </div>

        <div class="form-group">
            <input type="submit" class="btn" name="data_capture_contact_submit" value="Save Contact Details" />
        </div>
    </fieldset>
</form>
    <?php
} ?>
</section>