<?php
$form = \CPFCMembers\DataCaptureAddressForm::getInstance();
$templateData = \CPFCMembers\TemplateData::getInstance(); ?>

<section class="datacapture-panel">
<?php
switch ($templateData->get('status')) {
    case 'SUCCESS': ?>
<h1><?php the_field('data_capture_address_success_heading'); ?></h1>

<?php the_field('data_capture_address_success_copy'); ?>
    <span id="datacapture-success" data-result="true"></span>
    <?php
        break;

    default: ?>
<h1><?php the_field('data_capture_address_heading'); ?></h1>

<?php the_field('data_capture_address_copy'); ?>

<form class="datacapture" method="POST" action="<?php echo \CPFCMembers\Input::uri(); ?>">
    <fieldset>
        <input type="hidden" name="details" value="address" />
        <?php echo $form->securityField(); ?>

        <?php
        $form->displayError('form'); ?>

        <div class="form-block-wrap form-group <?php echo $form->displayErrorIndicator('house_name_number'); ?>">
            <label for="house-name-number" class="control-label">House Name/Number <?php echo $form->indicateRequired('house_name_number'); ?></label>
            <input class="form-control" type="text" name="house_name_number" id="house-name-number" placeholder="Your house name/number" value="<?php echo $form->getValue('house_name_number'); ?>" />
            <?php
            $form->displayError('house_name_number'); ?>
        </div>

        <div class="form-block-wrap form-group <?php echo $form->displayErrorIndicator('street'); ?>">
            <label for="street" class="control-label">Street <?php echo $form->indicateRequired('street'); ?></label>
            <input class="form-control" type="text" name="street" id="street" placeholder="Your street" value="<?php echo $form->getValue('street'); ?>" />
            <?php
            $form->displayError('street'); ?>
        </div>

        <div class="form-block-wrap form-group <?php echo $form->displayErrorIndicator('town'); ?>">
            <label for="town" class="control-label">Town <?php echo $form->indicateRequired('town'); ?></label>
            <input class="form-control" type="text" name="town" id="town" placeholder="Your town" value="<?php echo $form->getValue('town'); ?>" />
            <?php
            $form->displayError('town'); ?>
        </div>

         <div class="form-block-wrap form-group <?php echo $form->displayErrorIndicator('county'); ?>">
            <label for="county" class="control-label">County <?php echo $form->indicateRequired('county'); ?></label>
            <input class="form-control" type="text" name="county" id="county" placeholder="Your county" value="<?php echo $form->getValue('county'); ?>" />
            <?php
            $form->displayError('county'); ?>
        </div>

        <div class="form-block-wrap form-group <?php echo $form->displayErrorIndicator('postcode'); ?>">
            <label for="postcode" class="control-label">Postcode <?php echo $form->indicateRequired('postcode'); ?></label>
            <input class="form-control" type="text" name="postcode" id="postcode" placeholder="Your postcode" value="<?php echo $form->getValue('postcode'); ?>" />
            <?php
            $form->displayError('postcode'); ?>
        </div>

        <div class="form-block-wrap form-group country <?php echo $form->displayErrorIndicator('country'); ?>">
            <label for="country" class="control-label">Country <?php echo $form->indicateRequired('country'); ?></label>
            <select class="form-control" name="country" id="country" data-bv-notempty="true" data-bv-notempty-message="You must select a country">
                <option value="">Please select</option>
                <?php
                foreach ($form->getCountryOptions() as $countryCode => $countryName) { ?>
                <option value="<?php echo $countryCode; ?>" <?php echo $form->getValue('country') == $countryCode ? 'selected="selected"' : ''; ?>><?php echo $countryName; ?></option>
                <?php
                } ?>
            </select>
            <?php
            $form->displayError('country'); ?>
        </div>

        <div class="form-group">
            <input type="submit" class="btn" name="data_capture_address_submit" value="Save Address" />
        </div>
    </fieldset>
</form>
    <?php
} ?>
</section>