<?php
namespace CPFCMembers;

use \CPFCMembers\Input as Input;

class MembershipReporting
{
    protected static $_instance = null;

    public $defaultFilters;
    protected $_detailTableName, $_loginTableName, $_notificationTableName;
    protected $_memberColumns, $_loggedInColumns;
    protected $_sampleCount;

    public function __construct()
    {
        $this->_detailTableName       = 'member_details';
        $this->_loginTableName        = 'member_logins';
        $this->_notificationTableName = 'member_notifications';

        $this->defaultFilters = array(
            'registered_after' => date('Y-m-d'), // Beginning of today
            'logged_in_after' => date('Y-m-d'), // Beginning of today
        );

        $this->_sampleCount = 5;

        $this->_memberColumns = array(
            'title' => 'Title',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'dob' => 'DoB',
            'email_address' => 'Email Address',
            'daytime_phone' => 'Daytime Tel',
            'mobile_phone' => 'Mobile No.',
            'house_name_number' => 'Home Name/Number',
            'street' => 'Street',
            'town' => 'Town',
            'county' => 'County',
            'country' => 'Country',
            'postcode' => 'Postcode',
            'registration_status' => 'Registration Status',
            'status' => 'Status',
            'membership_level' => 'Membership Level',
            'preference_regular_newsletter' => 'Newsletter Opt-in',
            'preference_breaking_news' => 'News Opt-in',
            'preference_partners' => 'Partners Opt-in',
            'preference_sms' => 'SMS Opt-in',
            'see_tickets_account_number' => 'SEE Account No.',
            'loyalty_points' => 'Loyalty Points',
            'last_active' => 'Last Active',
            'last_login' => 'Last Login',
            'source' => 'Source',
            'registered_date' => 'Registration Date',
        );

        $this->_loggedInColumns = array(
            'login_date' => 'Login Date/Time',
            'title' => 'Title',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'dob' => 'DoB',
            'email_address' => 'Email Address',
            'daytime_phone' => 'Daytime Tel',
            'mobile_phone' => 'Mobile No.',
            'house_name_number' => 'Home Name/Number',
            'street' => 'Street',
            'town' => 'Town',
            'county' => 'County',
            'country' => 'Country',
            'postcode' => 'Postcode',
            'registration_status' => 'Registration Status',
            'status' => 'Status',
            'membership_level' => 'Membership Level',
            'preference_regular_newsletter' => 'Newsletter Opt-in',
            'preference_breaking_news' => 'News Opt-in',
            'preference_partners' => 'Partners Opt-in',
            'preference_sms' => 'SMS Opt-in',
            'see_tickets_account_number' => 'SEE Account No.',
            'loyalty_points' => 'Loyalty Points',
            'last_active' => 'Last Active',
            'last_login' => 'Last Login',
            'source' => 'Source',
            'registered_date' => 'Registration Date',
        );
    }

    /**
     * Get Instance of Members Class (Singleton)
     * @return object
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            $className = get_called_class();
            self::$_instance = new $className();
        }
        return self::$_instance;
    }

    /**
     * Initialisation method
     * @return NULL
     */
    public function init()
    {
        // Only run when Admin page is loaded
        if (Input::uri(false) == '/wp-admin/admin.php' && Input::request('page') == 'membership-reporting') {
            $templateData = \CPFCMembers\TemplateData::getInstance();
            $form = \CPFCMembers\ReportingFilter::getInstance();
            $export = Input::post('cpfc_filter_export') || Input::post('cpfc_filter_see_tickets_account_holder_export') || Input::post('cpfc_logged_in_filter_export');
            $filterResultsSample = false;
            $filterResultsCount = false;
            $exportLink = '';
            $dataType = '';

            // Define which tab to show in admin
            switch (true) {
                case Input::post('cpfc_logged_in_filter_submit'):
                    $showTab = '2';
                    break;
                case Input::post('cpfc_filter_see_tickets_account_holder_submit'):
                    $showTab = '1';
                    break;
                default:
                    $showTab = '0';
            }

            if (Input::request('cpfc_member_export')) {
                $this->memberExport();
            } elseif (Input::request('cpfc_login_export')) {
                $this->loginExport();
            } elseif (Input::isPost()) {
                $form->setValues(Input::post());
                $form->validate();

                if ($form->isValid()) {
                    $whereConditions = array();
                    $whereConditionsFriendly = array();

                    switch (true) {
                        case Input::post('cpfc_filter_member_submit'):
                        case Input::post('cpfc_filter_member_export'):
                        case Input::post('cpfc_filter_see_tickets_account_holder_submit'):
                        case Input::post('cpfc_filter_see_tickets_account_holder_export'):

                            list($whereConditions, $whereConditionsFriendly) = $this->buildWhereConditions();

                            $filterResultsSample = $this->fetchRegistrations($whereConditions, 0, $export ? false : $this->_sampleCount);

                            $filterResultsCount = $this->fetchRegistrations($whereConditions, 0, false, 'count');

                            $exportLink = home_url(CPFC_MEMBERS_REPORTING_ADMIN_URL, CPFC_MEMBERS_HTTP_MODE) . '&amp;' . http_build_query(Input::post() + array('cpfc_member_export'=>true));

                            $dataType = 'member';

                            break;
                        case Input::post('cpfc_logged_in_filter_submit'):
                        case Input::post('cpfc_logged_in_filter_export'):

                            list($whereConditions, $whereConditionsFriendly) = $this->buildWhereConditions();

                            $filterResultsSample = $this->fetchLogins($whereConditions, 0, $export ? false : $this->_sampleCount);

                            $filterResultsCount = $this->fetchLogins($whereConditions, 0, false, 'count');

                            $exportLink = home_url(CPFC_MEMBERS_REPORTING_ADMIN_URL, CPFC_MEMBERS_HTTP_MODE) . '&amp;' . http_build_query(Input::post() + array('cpfc_login_export'=>true));

                            $dataType = 'logins';

                            break;
                        default: // Reset
                            wp_redirect(home_url(CPFC_MEMBERS_REPORTING_ADMIN_URL, CPFC_MEMBERS_HTTP_MODE), 301);
                            exit();
                    }
                }

            } else {
                $form->setValues($this->defaultFilters);
            }

            $templateData->set('exportLink', $exportLink);
            $templateData->set('filterResultsCount', $filterResultsCount);
            $templateData->set('filterResultsSample', $filterResultsSample);
            $templateData->set('atAGlanceData', $this->fetchAtAGlance());
            $templateData->set('whereConditionsFriendly', $whereConditionsFriendly);
            $templateData->set('dataType', $dataType);
            $templateData->set('showTab', $showTab);
        }
    }

    /**
     * Initialise Class (for Admin)
     * @return NULL
     */
    public function adminInit()
    {
        // Only run when Admin page is loaded
        if (Input::uri(false) == '/wp-admin/admin.php' && Input::request('page') == 'membership-reporting') {
            // Add Javascript to Admin for Reporting
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script('jquery-ui-datepicker');

            wp_enqueue_style('cpfc-reporting-css', get_template_directory_uri() . '/members-module/assets/css/jquery-ui.css');
        }
    }

    /**
     * Fetch "At A Glance" data
     * @return array
     */
    public function fetchAtAGlance()
    {
        $now = time();
        $twentyFourHours = 86400;
        $sevenDays = $twentyFourHours * 7;
        return array(
            'registrationCountToday' => $this->registrationCount(array('registered_date >' => date('Y-m-d', $now) . ' 00:00:00')),
            'registrationCount24Hours' => $this->registrationCount(array('registered_date >' => date('Y-m-d H:i:s', $now - $twentyFourHours))),
            'registrationCount7Days' => $this->registrationCount(array('registered_date >' => date('Y-m-d H:i:s', $now - $sevenDays))),
            'loginCountToday' => $this->loginCount(array('login_date >' => date('Y-m-d', $now) . ' 00:00:00')),
            'loginCount24Hours' => $this->loginCount(array('login_date >' => date('Y-m-d H:i:s', $now - $twentyFourHours))),
            'loginCount7Days' => $this->loginCount(array('login_date >' => date('Y-m-d H:i:s', $now - $sevenDays))),
            'notificationDisplayCountToday' => $this->notificationCount(array('created_date >' => date('Y-m-d', $now) . ' 00:00:00')),
            'notificationDisplayCount24Hours' => $this->notificationCount(array('created_date >' => date('Y-m-d H:i:s', $now - $twentyFourHours))),
            'notificationDisplayCount7Days' => $this->notificationCount(array('created_date >' => date('Y-m-d H:i:s', $now - $sevenDays))),
            'notificationCompletedCountToday' => $this->notificationCount(array('created_date >' => date('Y-m-d', $now) . ' 00:00:00','status ='=>'complete')),
            'notificationCompletedCount24Hours' => $this->notificationCount(array('created_date >' => date('Y-m-d H:i:s', $now - $twentyFourHours),'status ='=>'complete')),
            'notificationCompletedCount7Days' => $this->notificationCount(array('created_date >' => date('Y-m-d H:i:s', $now - $sevenDays),'status ='=>'complete')),
        );
    }

    /**
     * Fetch registrations based on parsed "where" conditions
     * @param  array  $whereConditions  Where Conditions
     */
    public function fetchRegistrations($whereConditions = array(), $offset = 0, $count = false, $return = 'results')
    {
        global $wpdb;

        if ($return == 'count') {
            $sql = "SELECT COUNT(user_id) FROM {$wpdb->prefix}{$this->_detailTableName}";
        } else {
            $sql = "SELECT " . implode(',', array_keys($this->_memberColumns)) ." FROM {$wpdb->prefix}{$this->_detailTableName}";
        }

        // Don't include deleted registrations
        $whereConditions['status != '] = 'deleted';

        if ($whereConditions) {
            $values = array();
            $fields = array();

            foreach ($whereConditions as $field => $value) {
                $fields[] = $field . ' %s';
                $values[] = $value;
            }

            $sql .= ' WHERE ' . implode(' AND ', $fields);

            if ($count > 0 && $return == 'results') {
                $sql .= 'LIMIT ' . $count;
            }

            $sql .= ';';

            $sql = $wpdb->prepare($sql, $values);
        }

        if ($return == 'count') {
            return $wpdb->get_var($sql);
        }

        return $wpdb->get_results($sql, ARRAY_A);
    }

    /**
     * Fetch login data based on parsed "where" conditions
     * @param  array  $whereConditions  Where Conditions
     * @return int    Registration Count
     */
    public function fetchLogins($whereConditions = array(), $offset = 0, $count = false, $return = 'results')
    {
        global $wpdb;

        if ($return == 'count') {
            $sql = "SELECT COUNT(lt.id)";
        } else {
            $sql = "SELECT " . implode(',', array_keys($this->_loggedInColumns));
        }

        $sql .= " FROM {$wpdb->prefix}{$this->_loginTableName} lt
            LEFT JOIN {$wpdb->prefix}{$this->_detailTableName} dt ON lt.user_id = dt.user_id";

        // Don't include deleted registrations
        $whereConditions['status != '] = 'deleted';

        if ($whereConditions) {
            $values = array();
            $fields = array();

            foreach ($whereConditions as $field => $value) {
                $fields[] = $field . ' %s';
                $values[] = $value;
            }

            $sql .= ' WHERE ' . implode(' AND ', $fields);

            if ($count > 0 && $return == 'results') {
                $sql .= 'LIMIT ' . $count;
            }

            $sql .= ';';

            $sql = $wpdb->prepare($sql, $values);
        }

        if ($return == 'count') {
            return $wpdb->get_var($sql);
        }

        return $wpdb->get_results($sql, ARRAY_A);
    }

    /**
     * Build where conditions
     * @return array
     */
    public function buildWhereConditions()
    {
        $form = \CPFCMembers\ReportingFilter::getInstance();
        $whereConditions = array();
        $whereConditionsFriendly = array();

        switch (true) {
            case Input::request('cpfc_filter_member_submit'):
            case Input::request('cpfc_filter_member_export'):

                if (Input::request('registered_after')) {
                    $whereConditions['registered_date >='] = date('Y-m-d H:i:s', strtotime(Input::request('registered_after')));
                    $whereConditionsFriendly[] = 'Members were registered at or after ' . date('H:i \o\n jS F Y', strtotime(Input::request('registered_after')));
                }

                if (Input::request('registered_before')) {
                    $whereConditions['registered_date <'] = date('Y-m-d H:i:s', strtotime(Input::request('registered_before')));
                    $whereConditionsFriendly[] = 'Members were registered before ' . date('H:i \o\n jS F Y', strtotime(Input::request('registered_before')));
                }

                if (Input::request('country')) {
                    $whereConditions['country ='] = Input::request('country');

                    $countries = $form->getCountryOptions();
                    $whereConditionsFriendly[] = 'Members\' country is "' . $countries[Input::request('country')] . '"';
                }

                if (Input::request('membership_level')) {
                    $whereConditions['membership_level ='] = Input::request('membership_level');
                    $membershipLevels = $form->getMembershipLevelOptions();
                    $whereConditionsFriendly[] = 'Members\' membership level is "' . $membershipLevels[Input::request('membership_level')] . '"';
                }
                break;
            case Input::request('cpfc_filter_see_tickets_account_holder_submit'):
            case Input::request('cpfc_filter_see_tickets_account_holder_export'):

                if (Input::request('see_tickets_account_number')) {
                    $whereConditions['see_tickets_account_number ='] = Input::request('see_tickets_account_number');
                    $whereConditionsFriendly[] = 'Member\'s SEE Tickets Account Number is "' . Input::request('see_tickets_account_number') .'"';
                }
                break;
            case Input::post('cpfc_logged_in_filter_submit'):
            case Input::post('cpfc_logged_in_filter_export'):

                if (Input::request('logged_in_after')) {
                    $whereConditions['login_date >='] = date('Y-m-d H:i:s', strtotime(Input::request('logged_in_after')));
                    $whereConditionsFriendly[] = 'Members logged-in at or after ' . date('H:i \o\n jS F Y', strtotime(Input::request('logged_in_after')));
                }

                if (Input::request('logged_in_before')) {
                    $whereConditions['login_date <'] = date('Y-m-d H:i:s', strtotime(Input::request('logged_in_before')));
                    $whereConditionsFriendly[] = 'Members logged-in before ' . date('H:i \o\n jS F Y', strtotime(Input::request('logged_in_before')));
                }

            break;
        }

        return array($whereConditions, $whereConditionsFriendly);
    }

    /**
     * Fetch number of registrations based on parsed "where" conditions
     * @param  array  $whereConditions  Where Conditions
     * @return int    Registration Count
     */
    public function registrationCount($whereConditions = array())
    {
        global $wpdb;

        $sql = "SELECT COUNT(user_id) FROM {$wpdb->prefix}{$this->_detailTableName}";

        // Don't include deleted registrations
        $whereConditions['status != '] = 'deleted';

        if ($whereConditions) {
            $values = array();
            $fields = array();

            foreach ($whereConditions as $field => $value) {
                $fields[] = $field . ' %s';
                $values[] = $value;
            }

            $sql .= ' WHERE ' . implode(' AND ', $fields);

            $sql = $wpdb->prepare($sql, $values);
        }

        return $wpdb->get_var($sql);
    }

    /**
     * Fetch number of logins based on parsed "where" conditions
     * @param  array  $whereConditions  Where Conditions
     * @return int    Login Count
     */
    public function loginCount($whereConditions = array())
    {
        global $wpdb;

        $sql = "SELECT COUNT(id) FROM {$wpdb->prefix}{$this->_loginTableName}";

        if ($whereConditions) {
            $values = array();
            $fields = array();

            foreach ($whereConditions as $field => $value) {
                $fields[] = $field . ' %s';
                $values[] = $value;
            }

            $sql .= ' WHERE ' . implode(' AND ', $fields);

            $sql = $wpdb->prepare($sql, $values);
        }

        return $wpdb->get_var($sql);
    }

    /**
     * Fetch number of notifications based on parsed "where" conditions
     * @param  array  $whereConditions  Where Conditions
     * @return int    Registration Count
     */
    public function notificationCount($whereConditions = array())
    {
        global $wpdb;

        // Not a real notification, shouldn't be included in the numbers
        $whereConditions['detail != '] = 'registration_success';

        $sql = "SELECT COUNT(id) FROM {$wpdb->prefix}{$this->_notificationTableName}";

        if ($whereConditions) {
            $values = array();
            $fields = array();

            foreach ($whereConditions as $field => $value) {
                $fields[] = $field . ' %s';
                $values[] = $value;
            }

            $sql .= ' WHERE ' . implode(' AND ', $fields);

            $sql = $wpdb->prepare($sql, $values);
        }

        return $wpdb->get_var($sql);
    }

    /**
     * Export of Membership Data
     * @return NULL
     */
    public function memberExport()
    {
        $filename = 'cpfc-members-export-' . date('Y-m-d-his');
        list($whereConditions) = $this->buildWhereConditions();

        $exportData = $this->fetchRegistrations($whereConditions);

        $tmpFile = tmpfile();
        fputcsv($tmpFile , $this->_memberColumns);
        foreach ($exportData as $fields) {
            fputcsv($tmpFile , $fields);
        }

        header('Content-Type: application/download');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        rewind($tmpFile);
        fpassthru($tmpFile);
        fclose($tmpFile);

        die();
    }

    /**
     * Export of Login Data
     * @return NULL
     */
    public function loginExport()
    {
        $filename = 'cpfc-login-export-' . date('Y-m-d-his');
        list($whereConditions) = $this->buildWhereConditions();

        $exportData = $this->fetchLogins($whereConditions);

        $tmpFile = tmpfile();
        fputcsv($tmpFile , $this->_loggedInColumns);
        foreach ($exportData as $fields) {
            fputcsv($tmpFile , $fields);
        }

        header('Content-Type: application/download');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        rewind($tmpFile);
        fpassthru($tmpFile);
        fclose($tmpFile);

        die();
    }

    /**
     * Display Admin page
     * @return NULL
     */
    public function displayPage()
    {
        $templateData = \CPFCMembers\TemplateData::getInstance();
        $atAGlanceData = $templateData->get('atAGlanceData');
        $filterResultsSample = $templateData->get('filterResultsSample');
        $whereConditionsFriendly = $templateData->get('whereConditionsFriendly');
        $columns = $templateData->get('dataType') == 'logins' ? $this->_loggedInColumns : $this->_memberColumns; ?>

<div class="wrap">
    <h2>Membership Reporting</h2>
    <div style="float:left;width:60%;min-width:700px;padding-right:50px;">

        <?php
        get_partial('partials/reporting/filter'); ?>

    </div>

    <div style="float:left;width:35%;min-width:250px;margin-bottom:20px;">
        <h3>At a glance</h3>
        <p>Registrations Today: <strong><?php echo $atAGlanceData['registrationCountToday']; ?></strong><br />
        Registrations in last 24 hours: <strong><?php echo $atAGlanceData['registrationCount24Hours']; ?></strong><br />
        Registrations in last 7 days: <strong><?php echo $atAGlanceData['registrationCount7Days']; ?></strong></p>

        <p>Logins Today: <strong><?php echo $atAGlanceData['loginCountToday']; ?></strong><br />
        Logins in last 24 hours: <strong><?php echo $atAGlanceData['loginCount24Hours']; ?></strong><br />
        Logins in last 7 days: <strong><?php echo $atAGlanceData['loginCount7Days']; ?></strong></p>

        <p>Data Capture Notifications (Displayed/Completed) Today: <strong><?php echo $atAGlanceData['notificationDisplayCountToday']; ?> / <?php echo $atAGlanceData['notificationCompletedCountToday']; ?></strong><br />
        Data Capture Notifications (Displayed/Completed) in last 24 hours: <strong><?php echo $atAGlanceData['notificationDisplayCount24Hours']; ?> / <?php echo $atAGlanceData['notificationCompletedCount24Hours']; ?></strong><br />
        Data Capture Notifications (Displayed/Completed) in last 7 days: <strong><?php echo $atAGlanceData['notificationCompletedCount7Days']; ?> / <?php echo $atAGlanceData['notificationCompletedCount7Days']; ?></strong></p>
    </div>

    <div style="clear:both;width:100%;">

        <?php
        if ($filterResultsSample !== false) { ?>
        <h3>Filtered Results</h3>
        <h4>Applied Filters</h4>
        <?php
        if ($whereConditionsFriendly) { ?>
            <p>- <?php echo implode('<br/>- ', $whereConditionsFriendly); ?></p>
        <?php
        } ?>
        <?php
            if ($filterResultsSample) {
                $rowCount = count($filterResultsSample); ?>
                <p><strong>This is a sample set of <?php echo $rowCount . ($rowCount == 1 ? ' row' : ' rows'); ?> of data that would be exported using the current filters.</strong></p>

        <table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <?php
                    if ($columns['login_date']) { ?>
                    <th><?php echo $columns['login_date']; ?></th>
                    <?php
                    } ?>
                    <th><?php echo $columns['first_name']; ?></th>
                    <th><?php echo $columns['last_name']; ?></th>
                    <th><?php echo $columns['dob']; ?></th>
                    <th><?php echo $columns['email_address']; ?></th>
                    <th><?php echo $columns['registration_status']; ?></th>
                    <th><?php echo $columns['registered_date']; ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($filterResultsSample as $filterResult) { ?>
                <tr>
                    <?php
                    if ($filterResult['login_date']) { ?>
                    <td><?php echo $filterResult['login_date']; ?></td>
                    <?php
                    } ?>
                    <td><?php echo $filterResult['first_name']; ?></td>
                    <td><?php echo $filterResult['last_name']; ?></td>
                    <td><?php echo $filterResult['dob']; ?></td>
                    <td><?php echo $filterResult['email_address']; ?></td>
                    <td><?php echo $filterResult['registration_status']; ?></td>
                    <td><?php echo $filterResult['registered_date']; ?></td>
                </tr>
                <?php
                } ?>
            </tbody>
        </table>

        <p><a href="<?php echo $templateData->get('exportLink'); ?>" class="button button-primary">Export all <?php echo $templateData->get('filterResultsCount'); ?> <?php echo $templateData->get('filterResultsCount') == 1 ? 'row' : 'rows'; ?> of data</a></p>
            <?php
            } else {
                ?><p><strong>No results were found with the current filters.</strong></p><?php
            }
        } ?>

    </div>

</div>

<?php
    }
}