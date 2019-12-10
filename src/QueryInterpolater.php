<?php
namespace Kumamidori\DevPdoStatement;

final class QueryInterpolater
{
    /**
     * Replaces any parameter placeholders in a query with the value of that
     * parameter. Useful for debugging. Assumes anonymous parameters from
     * $params are are in the same order as specified in $query
     *
     * @param string $query  The sql query with parameter placeholders
     * @param array  $params The array of substitution parameters
     *
     * @return string The interpolated query
     *
     * @link http://stackoverflow.com/a/8403150
     * thanks
     */
    public function interpolate($query, $params) {
        $keys = array();
        $values = $params;
        $values_limit = [];

        $words_repeated = array_count_values(str_word_count($query, 1, ':_'));

        # build a regular expression for each parameter
        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/:'.$key.'/';
                $values_limit[$key] = (isset($words_repeated[':'.$key]) ? intval($words_repeated[':'.$key]) : 1);
            } else {
                $keys[] = '/[?]/';
                $values_limit = [];
            }

            if (is_string($value))
                $values[$key] = "'" . $value . "'";

            if (is_array($value))
                $values[$key] = "'" . implode("','", $value) . "'";

            if (is_null($value))
                $values[$key] = 'NULL';
        }

        if (is_array($values)) {
            foreach ($values as $key => $val) {
                if (isset($values_limit[$key])) {
                    $query = preg_replace(['/:'.$key.'/'], [$val], $query, $values_limit[$key], $count);
                } else {
                    $query = preg_replace(['/:'.$key.'/'], [$val], $query, 1, $count);
                }
            }
            unset($key, $val);
        } else {
            $query = preg_replace($keys, $values, $query, 1, $count);
        }
        unset($keys, $values, $values_limit, $words_repeated);

        return $query;
    }
}
