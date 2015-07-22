<?php
$form = \CPFCMembers\DataCaptureSeeTicketsForm::getInstance();
$templateData = \CPFCMembers\TemplateData::getInstance(); ?>

<section class="datacapture-panel">
<?php
switch ($templateData->get('status')) {
    case 'SUCCESS': ?>
<h1><?php the_field('data_capture_see_tickets_success_heading'); ?></h1>

<?php the_field('data_capture_see_tickets_success_copy'); ?>
    <span id="datacapture-success" data-result="true"></span>
    <?php
        break;

    default: ?>
<h1><?php the_field('data_capture_see_tickets_heading'); ?></h1>

<?php the_field('data_capture_see_tickets_copy'); ?>

<form class="datacapture" method="POST" action="<?php echo \CPFCMembers\Input::uri(); ?>">
    <fieldset>
        <input type="hidden" name="details" value="see_tickets" />
        <?php echo $form->securityField(); ?>

        <?php
        $form->displayError('form'); ?>

        <div class="form-block-wrap form-group <?php echo $form->displayErrorIndicator('see_tickets_account_number'); ?>">
            <label for="see-tickets-account-number" class="control-label accessibility">SEE Tickets Acc. No. <?php echo $form->indicateRequired('see_tickets_account_number'); ?></label>
            <input class="form-control" type="text" name="see_tickets_account_number" id="see-tickets-account-number" placeholder="Your SEE Tickets Account Number" data-bv-notempty="false" data-bv-notempty-message="SEE Tickets Account Number is required and cannot be empty" value="<?php echo $form->getValue('see_tickets_account_number'); ?>" <?php echo \CPFCMembers\Auth::getUser()->getSeeTicketsAccountNumber() ? 'disabled="disabled"' : ''; ?>/>
            <?php
            $form->displayError('see_tickets_account_number'); ?>
        </div>

        <div class="form-group">
            <input type="submit" class="btn" name="data_capture_see_tickets_submit" value="Save" />
        </div>
    </fieldset>
</form>
    <?php
} ?>
</section>