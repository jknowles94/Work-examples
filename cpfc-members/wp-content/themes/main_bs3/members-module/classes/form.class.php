<?php
namespace CPFCMembers;

class Form
{
    protected static $_instance = null;

    protected $_rawValues;
    protected $_filteredValues;
    protected $_valid;
    protected $_errorMessages;
    protected $_successMessage;
    protected $_requiredFields = array();
    protected $_nonceIdentifier = '';
    protected $_nonceFieldName = 'security';
    protected $_securityMessage = 'For security reasons this form could not be submitted. Please reload the page and re-submit the form.';

    public function __construct()
    {
        $this->_rawValues = array();
        $this->_filteredValues = array();
        $this->resetErrors();
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
     * Filter all variables that require it
     */
    public function processFilters()
    {
        return $this;
    }

    /**
     * Store values from the form submission
     */
    public function setValues($data)
    {
        $this->_rawValues = array_merge($this->_rawValues, $data);

        return $this;
    }

    /**
     * Get the value from the form, or return "null" if it's not set
     */
    public function getValue($index, $arrayIndex = false)
    {
        if ($arrayIndex && isset($this->_rawValues[$index]) && is_array($this->_rawValues[$index])) {
            return isset($this->_rawValues[$index][$arrayIndex]) ? $this->_rawValues[$index][$arrayIndex] : null;
        }

        return isset($this->_rawValues[$index]) ? $this->_rawValues[$index] : null;
    }

    /**
     * Filter all variables that require it
     */
    public function getFilteredValues()
    {
        return $this->_filteredValues;
    }

    /**
     * Get all form values
     */
    public function getValues()
    {
        return $this->_rawValues;
    }

    /**
     * Validate form
     */
    public function validate()
    {
        $this->processFilters();

        $this->validateSecurity();

        return $this;
    }

    /**
     * Validate Required field
     */
    public function validateRequired($fieldName, $index = false)
    {
        if (in_array($fieldName, $this->_requiredFields)) {
            $value = $this->getValue($fieldName, $index);
            return is_null($value) || !strlen($value);
        }

        return false;
    }

    /**
     * Validate Security field
     */
    public function validateSecurity()
    {
        if (!Input::post($this->_nonceFieldName) || !wp_verify_nonce(Input::post($this->_nonceFieldName), $this->_nonceIdentifier)) {
            $this->addError('form', $this->_securityMessage);
        }

        return false;
    }

    /**
     * Mark form invalid
     */
    public function markInvalid()
    {
        $this->_valid = false;

        return $this;
    }

    /**
     * Does the form data meet the form's validation criteria
     * @return boolean
     */
    public function isValid()
    {
        return $this->_valid;
    }

    /**
     * Reset Error Messages
     */
    public function resetErrors()
    {
        $this->_errorMessages = array();
        $this->_valid = true;

        return $this;
    }

    /**
     * Add Error Message to stack
     * @param string $index   Index, most likely the field name
     * @param string $message Error Message
     */
    public function addError($index, $message)
    {
        $this->_errorMessages[$index] = $message;
        $this->markInvalid();

        return $this;
    }

    /**
     * Get Error Message from stack, if it exists
     * @param string $index   Index, most likely the field name
     */
    public function getError($index)
    {
        return isset($this->_errorMessages[$index]) ? $this->_errorMessages[$index] : '';
    }


    /**
     * Set Success Message
     * @param string $s   The success message
     */
    public function setSuccess($s)
    {
        $this->_successMessage = $s;
    }

    /**
     * Get Success Message if it exists
     */
    public function getSuccess()
    {
        return $this->_successMessage;
    }

    /**
     * Diplay error class to input field wrapper, if an error exists
     * @param string $index   Index, most likely the field name
     */
    public function displayErrorIndicator($index)
    {
        return isset($this->_errorMessages[$index]) ? 'has-error' : '';
    }

    /**
     * Get all Error Messages from stack
     * @param string $index   Index, most likely the field name
     */
    public function getErrors()
    {
        return $this->_errorMessages;
    }

    /**
     * Get the markup for the nonce field
     */
    public function securityField()
    {
        return wp_nonce_field($this->_nonceIdentifier, 'security', true, false);
    }

    /**
     * Indicate the field is required on the Frontend
     * @param  string  $fieldName Name of the field you need to indicate
     * @return string  Either the HTML to show a field is required, or an empty string
     */
    public function indicateRequired($fieldName)
    {
        return in_array($fieldName, $this->_requiredFields) ? '*' : '';
    }

    /**
     * Display the error markup (if an error for that field exists)
     * @return string  Either the  HTML to show the success message, or an empty string
     */
    public function displayError($fieldName)
    {
        if ($this->getError($fieldName)) { ?>
        <small class="help-block" data-bv-server-side-field="<?php echo $fieldName;?>" id="ssv-<?php echo $fieldName;?>"><?php echo $this->getError($fieldName); ?></small>
        <?php
        }
    }

    /**
     * Display the success markup (if a message for that field exists)
     * @param  string  $fieldName  Name of the field
     * @return string  Either the  HTML to show the field's error, or an empty string
     */
    public function displaySuccess()
    {
        if ($this->getSuccess()) { ?>
        <div class="message success"><?php echo $this->getSuccess(); ?></div>
        <?php
        }
    }

    /**
     * Return list of options for Title
     */
    public static function getTitleOptions()
    {
        return array(
            'Mr',
            'Mrs',
            'Ms',
            'Miss',
        );
    }

    /**
     * Return possible day options for the date
     * @return array  List of days
     */
    public function getDateDayOptions()
    {
        return array(
            '01','02','03','04','05','06','07',
            '08','09','10','11','12','13','14',
            '15','16','17','18','19','20','21',
            '22','23','24','25','26','27','28',
            '29','30','31',
        );
    }

    /**
     * Return possible month options for the date
     * @return array  List of months
     */
    public function getDateMonthOptions()
    {
        return array(
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        );
    }

    /**
     * Return possible year options for the date
     * @return array  List of years
     */
    public function getDateYearOptions()
    {
        return range(date('Y') - 13, 1900, -1);
    }

    /**
     * Return possible membership level options
     * @return array  List of levels
     */
    public function getMembershipLevelOptions()
    {
        return \CPFCMembers\MembershipLevelModel::fetchAll();
    }

    /**
     * Return array of all countries
     * @return array  List of countries
     */
    public static function getCountryOptions()
    {
        return array(
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'AF' => 'Afghanistan',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'BQ' => 'British Antarctic Territory',
            'IO' => 'British Indian Ocean Territory',
            'VG' => 'British Virgin Islands',
            'BN' => 'Brunei',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CT' => 'Canton and Enderbury Islands',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos [Keeling] Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo - Brazzaville',
            'CD' => 'Congo - Kinshasa',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'CI' => 'Côte d’Ivoire',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'NQ' => 'Dronning Maud Land',
            'DD' => 'East Germany',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'FQ' => 'French Southern and Antarctic Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island and McDonald Islands',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong SAR China',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JT' => 'Johnston Island',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Laos',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macau SAR China',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'FX' => 'Metropolitan France',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MI' => 'Midway Islands',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar [Burma]',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'AN' => 'Netherlands Antilles',
            'NT' => 'Neutral Zone',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'KP' => 'North Korea',
            'VD' => 'North Vietnam',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PC' => 'Pacific Islands Trust Territory',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territories',
            'PA' => 'Panama',
            'PZ' => 'Panama Canal Zone',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'YD' => 'People\'s Democratic Republic of Yemen',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn Islands',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RO' => 'Romania',
            'RU' => 'Russia',
            'RW' => 'Rwanda',
            'RE' => 'Réunion',
            'BL' => 'Saint Barthélemy',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin',
            'PM' => 'Saint Pierre and Miquelon',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'CS' => 'Serbia and Montenegro',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia and the South Sandwich Islands',
            'KR' => 'South Korea',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard and Jan Mayen',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syria',
            'ST' => 'São Tomé and Príncipe',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands',
            'TV' => 'Tuvalu',
            'UM' => 'U.S. Minor Outlying Islands',
            'PU' => 'U.S. Miscellaneous Pacific Islands',
            'VI' => 'U.S. Virgin Islands',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'SU' => 'Union of Soviet Socialist Republics',
            'AE' => 'United Arab Emirates',
            'ZZ' => 'Unknown or Invalid Region',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VA' => 'Vatican City',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'WK' => 'Wake Island',
            'WF' => 'Wallis and Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
            'AX' => 'Åland Islands',
        );
    }
}