<?php
$form = \CPFCMembers\DataCapturePreferencesForm::getInstance();
$templateData = \CPFCMembers\TemplateData::getInstance(); ?>

<section class="datacapture-panel">
<?php
switch ($templateData->get('status')) {
    case 'SUCCESS': ?>
<h1><?php the_field('data_capture_preferences_success_heading'); ?></h1>

<?php the_field('data_capture_preferences_success_copy'); ?>
    <span id="datacapture-success" data-result="true"></span>
    <?php
        break;

    default: ?>
<h1><?php the_field('data_capture_preferences_heading'); ?></h1>

<?php the_field('data_capture_preferences_copy'); ?>

<form class="datacapture" method="POST" action="<?php echo \CPFCMembers\Input::uri(); ?>">
    <fieldset>
        <input type="hidden" name="details" value="preferences" />
        <?php echo $form->securityField(); ?>

        <?php
        $form->displayError('form'); ?>

        <div class="form-block-wrap form-group">
            <div class="check">
                <input class="form-control" type="checkbox" name="preference_regular_newsletter" id="preference-regular-newsletter" value="1" <?php echo $form->getValue('preference_regular_newsletter') ? 'checked="checked"' : ''; ?> />
                <label for="preference-regular-newsletter" class="control-label"><?php the_field('email_preferences_regular_newsletter_text', 'option'); ?></label>
            </div>

            <div class="check">
                <input class="form-control" type="checkbox" name="preference_breaking_news" id="preference-breaking-news" value="1" <?php echo $form->getValue('preference_breaking_news') ? 'checked="checked"' : ''; ?> />
                <label for="preference-breaking-news" class="control-label"><?php the_field('email_preferences_breaking_news_text', 'option'); ?></label>
            </div>

            <div class="check">
                <input class="form-control" type="checkbox" name="preference_partners" id="preference-partners" value="1" <?php echo $form->getValue('preference_partners') ? 'checked="checked"' : ''; ?> />
                <label for="preference-partners" class="control-label"><?php the_field('email_preferences_partners_text', 'option'); ?></label>
            </div>
        </div>

        <div class="form-group">
            <input type="submit" class="btn" name="data_capture_preferences_submit" value="Save Preferences" />
        </div>
    </fieldset>
</form>
    <?php
} ?>
</section>