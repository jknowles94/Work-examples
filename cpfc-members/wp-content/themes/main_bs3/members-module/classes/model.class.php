<?php
namespace CPFCMembers;

class Model
{
    /**
     * Constructor
     */
    public function __construct() {}

    /**
     * Getter
     * @param  string $propertyName  Name of Property
     * @return mixed                 The property's value
     */
    public function __get($propertyName)
    {
        return $this->{$propertyName};
    }

    /**
     * Setter
     * @param  string $propertyName  Name of Property
     * @param  mixed $value          The value to store
     * @return Model                 The Model
     */
    public function __set($propertyName, $value)
    {
        $this->{$propertyName} = $value;

        return $this;
    }

    /**
     * Save Model's data
     * @return boolean  Was the save successful?
     */
    public function save() {}

    /**
     * Convert empty field to NULL
     * @return [type] [description]
     */
    public function convertEmptyToNull($values)
    {
        $newValues = array();
        foreach ($values as $key => $val) {
            $newValues[$key] = $val == '' || is_null($val) ? 'NULL' : $val;
        }

        return $newValues;
    }

    /**
     * Safe DB insert that allows NULL values
     * @return mixed
     */
    protected function _safeInsert($tableName, $values, $formats, $saveEmptyAsNull = false)
    {
        global $wpdb;

        if ($saveEmptyAsNull) {
            $values = $this->convertEmptyToNull($values);

            $includableFields = array();
            $includableValues = array();
            $includableFormats = array();

            foreach ($values as $fieldName => $value) {
                $format = array_shift($formats);

                $includableFields[] = $fieldName;

                if ($value == 'NULL') {
                    $includableFormats[] = 'NULL';
                } else {
                    $includableValues[] = $value;
                    $includableFormats[] = $format;
                }
            }

            $sql = "INSERT INTO {$tableName} ";
            $sql .= '(' . implode(', ', $includableFields) . ')';
            $sql .= ' VALUES (' . implode(',', $includableFormats) . ');';

            $preparedSql = $wpdb->prepare($sql, $includableValues);

            if ($wpdb->query($preparedSql) != false) {
                return $wpdb->insert_id;
            }

            return false;

        }

        return $wpdb->insert($tableName, $values, $formats);
    }

    /**
     * Safe DB update that allows NULL values
     * @return mixed
     */
    protected function _safeUpdate($tableName, $values, $formats, $where = array(), $whereFormats = array(), $saveEmptyAsNull = false)
    {
        global $wpdb;

        if ($saveEmptyAsNull) {
            $values = $this->convertEmptyToNull($values);

            $includableFields = array();
            $includableValues = array();
            $i = 0;
            foreach ($values as $fieldName => $value) {
                $format = array_shift($formats);

                //$includableFields[] = $fieldName;

                if ($value == 'NULL') {
                    $includableFields[] = "`{$fieldName}` = NULL";

                } else {
                    $includableFields[] = "{$fieldName} = {$format}";
                    $includableValues[] = $value;
                }
            }

            $sql = "UPDATE {$tableName} ";
            $sql .= 'SET ' . implode(', ', $includableFields) . '';

            if ($where) {
                $sql .= " WHERE ";

                $whereConditions = array();
                foreach ($where as $fieldName => $value) {
                    $format = array_shift($whereFormats);
                    $whereConditions[] = "{$fieldName} = {$format}";
                    $includableValues[] = $value;
                }

                $sql .= implode(' AND ', $whereConditions) . '';
            }

            $sql .= ';';

            $preparedSql = $wpdb->prepare($sql, $includableValues);

            if ($wpdb->query($preparedSql) != false) {
                return true;
            }

            return false;

        }

        return $wpdb->update($tableName, $values, $where, $formats, $whereFormats);
    }
}