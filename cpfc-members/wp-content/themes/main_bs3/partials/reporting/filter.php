<?php
$templateData = \CPFCMembers\TemplateData::getInstance();
$form = \CPFCMembers\ReportingFilter::getInstance(); ?>

<h3>Filters</h3>

<form method="POST" action="<?php echo home_url(CPFC_MEMBERS_REPORTING_ADMIN_URL, CPFC_MEMBERS_HTTP_MODE); ?>">
    <div id="tabs">
        <ul>
            <li><a href="#fragment-1"><span>Members by Date/Country</span></a></li>
            <li><a href="#fragment-2" class="active"><span>Members by SEE Tickets No.</span></a></li>
            <li><a href="#fragment-3"><span>Member Logins by Date</span></a></li>
        </ul>
        <div id="fragment-1">
            <p>
                <label for="registered-after">Registered After</label>
                <input type="text" name="registered_after" class="cpfc-datepicker" id="registered-after" value="<?php echo $form->getValue('registered_after'); ?>" autocomplete="off">

                <label for="registered-before">Registered Before</label>
                <input type="text" name="registered_before" class="cpfc-datepicker" id="registered-before" value="<?php echo $form->getValue('registered_before'); ?>"autocomplete="off">
            </p>

            <?php
            if ($form->getError('registration_date')) { ?>
            <p style="color:red;"><?php echo $form->getError('registration_date'); ?></p>
            <?php
            } ?>

            <p>
                <label for="country">Country</label>
                <select name="country" id="country">
                    <option value="">All Countries</option>
                    <?php
                    foreach ($form->getCountryOptions() as $countryCode => $countryName) { ?>
                    <option value="<?php echo $countryCode; ?>" <?php echo $form->getValue('country') == $countryCode ? 'selected="selected"' : ''; ?>><?php echo $countryName; ?></option>
                    <?php
                    } ?>
                </select>
            </p>

            <?php /*<p>
                <label for="membership-level">Membership Level</label>
                <select name="membership_level" id="membership-level">
                    <option value="">All Levels</option>
                    <?php
                    foreach ($form->getMembershipLevelOptions() as $membershipValue => $membershipName) { ?>
                    <option value="<?php echo $membershipValue; ?>" <?php echo $form->getValue('membership_level') == $membershipValue ? 'selected="selected"' : ''; ?>><?php echo $membershipName; ?></option>
                    <?php
                    } ?>
                </select>
            </p> */ ?>

            <p>
                <input type="submit" name="cpfc_filter_member_submit" class="button button-primary" value="Filter Memberships" />
                <input type="submit" name="cpfc_filter_member_reset_submit" class="button" value="Reset" />
            </p>
        </div>
        <div id="fragment-2">

            <p>
                <label for="country">SEE Tickets Account Number</label>
                <input type="text" name="see_tickets_account_number" id="see-tickets-account-number" value="<?php echo $form->getValue('see_tickets_account_number'); ?>"autocomplete="off">
            </p>

            <?php
            if ($form->getError('see_tickets_account_number')) { ?>
            <p style="color:red;"><?php echo $form->getError('see_tickets_account_number'); ?></p>
            <?php
            } ?>

            <p>
                <input type="submit" name="cpfc_filter_see_tickets_account_holder_submit" class="button button-primary" value="Find Account Holder" />
                <input type="submit" name="cpfc_filter_reset_submit" class="button" value="Reset" />
            </p>
        </div>
        <div id="fragment-3">

            <p>
                <label for="logged-in-after">Logged-in After</label>
                <input type="text" name="logged_in_after" class="cpfc-datepicker" id="logged-in-after" value="<?php echo $form->getValue('logged_in_after'); ?>" autocomplete="off">

                <label for="logged-in-before">Logged-in Before</label>
                <input type="text" name="logged_in_before" class="cpfc-datepicker" id="logged-in-before" value="<?php echo $form->getValue('logged_in_before'); ?>"autocomplete="off">
            </p>

            <p>
                <input type="submit" name="cpfc_logged_in_filter_submit" class="button button-primary" value="Filter Logins" />
                <input type="submit" name="cpfc_logged_in_filter_reset_submit" class="button" value="Reset" />
            </p>

            <?php
            if ($form->getError('logged_in_date')) { ?>
            <p style="color:red;"><?php echo $form->getError('logged_in_date'); ?></p>
            <?php
            } ?>
        </div>
    </div>
</form>

<script type="text/javascript">
jQuery(document).ready(function($) {
    $("#tabs").tabs({
      active: <?php echo $templateData->get('showTab'); ?>
    });

    $(".cpfc-datepicker").datepicker({
        dateFormat: "yy-mm-dd"
    });
});
</script>