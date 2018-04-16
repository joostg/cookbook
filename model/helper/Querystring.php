<?php
namespace cookbook\model\helper;
/**
 * Handles any query string and provides easy methods for setting and getting its parameters
 */
class queryString
{
    /**
     * @var array to hold the separate parameters
     */
    protected $_params = array();

    /**
     * Sets the query string based on the query string received by the webserver
     * or the given query string
     *
     * @param string $qs optional query string to use instead of $_SERVER['QUERY_STRING']
     */
    public function __construct($qs = null)
    {
        $params = '';

        // fix for using this class in a cli script (ie: unit test) - QUERY_STRING will not be set
        if (is_null($qs) && isset($_SERVER['QUERY_STRING'])) {
            $params = $_SERVER['QUERY_STRING'];
        }

        if ($qs != '') {
            // remove leading and trailing ampersands, and any leading question mark
            $params = trim($qs, '?&');
        }

        $params = $this->_split($params);

        foreach ($params as $param) {
            $this->set($param, null, false);
        }
    }

    /**
     * Output the entire query string with urlencoded parameters (spaces are '%20', not '+')
     *
     * @return string the query string, suitable for use in urls
     */
    public function output()
    {
        $output = array();

        foreach ($this->_params as $param => $value) {
            if (!is_array($value)) {
                $output[] = rawurlencode($param) . '=' . rawurlencode($value);
            } else {
                foreach ($value as $item) {
                    $output[] = rawurlencode($param) . '=' . rawurlencode($item);
                }
            }
        }

        $output = implode('&', $output);

        return $output;
    }

    /**
     * Returns all set parameters as an array
     *
     * @return array all parameters
     */
    public function getAll()
    {
        return $this->_params;
    }

    /**
     * Get the given parameter from the query string in rawurlencoded "param=value" format.
     * Will return a "&"-concatenated and rawurlencoded list if the parameter has multiple values
     *
     * @param string $param the name of the parameter to get
     * @param boolean $valuesOnly if set, return value is string or array of strings with value(s)
     * @return string "param=value" of the parameter or "&"-concatenated list when multiple
     */
    public function get($param, $valuesOnly = false)
    {
        $return = '';

        if (isset($this->_params[$param])) {
            if (is_array($this->_params[$param])) {
                $parts = array();
                foreach ($this->_params[$param] as $value) {
                    $parts[] = rawurlencode($param) . '=' . rawurlencode($value);
                    $values[] = $value;
                }
                $return = implode('&', $parts);

                if ($valuesOnly) {
                    $return = $values;
                }
            } else {
                $return = rawurlencode($param) . '=' . rawurlencode($this->_params[$param]);

                if ($valuesOnly) {
                    $return = $this->_params[$param];
                }
            }
        }

        return $return;
    }

    /**
     * Get the (unencoded) value of the given parameter from the query string.
     * Will return the first value encountered
     *
     * @param string $param the name of the parameter to get the value(s) of
     * @return string value of the parameter
     */
    public function getValue($param)
    {
        $value = $this->get($param, true);
        if (is_array($value)) {
            $value = $value[0];
        }

        return $value;
    }

    /**
     * Get the (unencoded) values of the given parameter from the query string.
     * Will return an array of values
     *
     * @param string $param the name of the parameter to get the value(s) of
     * @return array of values
     */
    public function getValues($param)
    {
        $values = $this->get($param, true);

        if (empty($values)) {
            return array();
        }

        if (!is_array($values)) {
            $values = array($values);
        }

        return $values;
    }

    /**
     * Add (or overwrite) the given parameter to (in) the query string.
     * If not overwriting an existing parameter, another parameter with the same name will be added.
     *
     * @param string $param the name of the parameter to set
     * @param string $param the name and value of the parameter to set, in "param=value" format
     * @param string $param a list of names and values, in "param=value1&param=value2" format
     * @param array $param associative array of names (keys) and values. $value param is ignored
     * @param string $value the value to set the parameter to or null if value is in param variable
     * @param boolean $overwrite if an existing parameter with that name should be overwritten
     * @return boolean true on success, false on error
     */
    public function set($param, $value = null, $overwrite = true)
    {
        if (empty($param)) {
            return false;
        }

        if (is_array($param)) {
            foreach ($param as $name => $value) {
                $this->set($name, $value, $overwrite);
            }

            return true;
        }

        // handle case where param contains multiple values
        // e.g.: warehouses=C&amp;A&warehouses=V&amp;D
        $paramList = $this->_split($param);

        // only when splitting up the string resulted in more than one parameter, process them
        if (sizeof($paramList) > 1) {
            foreach ($paramList as $param) {
                $this->set($param, null, false);
            }

            return true;
        }

        // handle case where value is contained in $param
        // it is assumed that in this situation the parameter and its value are urlencoded
        if ($value === null) {
            // set an empty value if the parameter has none
            $value = '';
            $param = explode('=', $param);
            if (isset($param[1])) {
                $value = urldecode($param[1]);
            }

            $param = urldecode($param[0]);
        }

        // if this is a new parameter or overwrite is true, add the parameter as a string value
        // when overwriting, we can't use arrays since we don't know which element to replace
        if (!array_key_exists($param, $this->_params) || $overwrite == true) {
            $this->_params[$param] = $value;
        } else {
            if (array_key_exists($param, $this->_params) && !is_array($this->_params[$param])) {
                // the first element is present as a string, convert into array first
                $this->_params[$param] = array($this->_params[$param]);
            }

            // add new parameter as array element
            $this->_params[$param][] = $value;
        }

        return true;
    }

    /**
     * Split the given parameters into array elements
     *
     * @param string $params the list of parameters to split up
     * @return array with all parameters, still in "param=value" format
     */
    protected function _split($params)
    {
        $splitParams = array();

        // temporarily turn encoded ampersands into another identifiable string
        // so the actual parameters can be split correctly
        $ampersandsReplaced = str_replace('&amp;', '%%ampersand%%', $params);
        $paramList = explode('&', $ampersandsReplaced);

        foreach ($paramList as $param) {
            // add the parameter, returning any ampersands to their original, encoded form
            $param = str_replace('%%ampersand%%', '&amp;', $param);
            $splitParams[] = $param;
        }

        return $splitParams;
    }

    /**
     * Checks if the given parameter is set in the query string
     *
     * @param string $param the name of the parameter to check
     * @param boolean $exact check the given parameter exactly, including capitalization
     * @return boolean true if the parameter is set, false if not
     */
    public function isPresent($param, $exact = true)
    {
        $keys = array_keys($this->_params);
        if (!$exact) {
            $keys = array_map(function($v) {return strtolower($v);}, $keys);
        }

        if (in_array($param, $keys)) {
            return true;
        }

        return false;
    }

    /**
     * Remove the given parameter.
     * If value is given, remove only that value instead of all possible values of that parameter
     *
     * @param string $param the parameter to remove
     * @param string $value optional. If given, only remove that value.
     * @return boolean true on success, false on error.
     */
    public function remove($param, $value = null)
    {
        if (!$this->IsPresent($param)) {
            return true;
        }

        if (is_null($value) || !is_array($this->_params[$param])) {
            unset($this->_params[$param]);

            return true;
        }

        if (is_array($this->_params[$param])) {
            foreach ($this->_params[$param] as $key => $setValue) {
                if ($setValue == $value) {
                    unset($this->_params[$param][$key]);
                    // make sure indices are renumbered
                    $this->_params[$param] = array_values($this->_params[$param]);

                    return true;
                }
            }
        }

        return false;
    }

    public function getFilterValue($param)
    {
        $filterValue = null;
        $f = $this->getValues('f');

        foreach ($f as $filter) {
            $filterData = explode(':', $filter);

            if ($filterData[0] == $param) {
                $filterValue = $filterData[1];
                break;
            }
        }

        return $filterValue;
    }

    public function getQueryAndFilters()
    {
        $return = array();
        $qs = $this->getAll();

        // get query if not null
        if (isset($qs['q'])) {
            if ($qs['q']) {
                $return['query'] = $qs['q'];
            }
        }

        // add filters if not null
        if (isset($qs['f'])) {
            if (is_array($qs['f'])) {
                foreach ($qs['f'] as $filter) {
                    $filterData = explode(':', $filter);

                    if ($filterData[1]) {
                        $key = $filterData[0];
                        $value = $filterData[1];

                        if (isset($return[$key])) {
                            if (!is_array($return[$key])) {
                                $oldValue = $return[$key];

                                $return[$key] = array($oldValue);
                            }

                            $return[$key][] = $value;
                        } else {
                            $return[$key] = $value;
                        }
                    }
                }
            } else {
                $filterData = explode(':', $qs['f']);

                if ($filterData[1]) {
                    $return[$filterData[0]] = $filterData[1];
                }
            }
        }

        // add remaining items, making sure it's not a reserved key
        $reservedKeys = array('q','f','l','s','o','c','p');

        foreach ($qs as $key => $value) {
            if (in_array($key, $reservedKeys)) {
                continue;
            }

            if ($value) {
                $return[$key] = $value;
            }
        }

        return $return;
    }

    public function getQueryData()
    {
        // default paging values
        $parameters = array(
            'limit' => 25,
            'sort'  => 'updated_at',
            'order' => 'DESC',
            'page'  => 1
        );

        foreach ($this->_params as $key => $value) {
            switch ($key) {
                case 'q':
                    if ($value != '') {
                        $parameters['query'] = $value;
                    }
                    break;
                case 'l':
                    if ($value != '') {
                        $parameters['limit'] = $value;
                    }
                    break;
                case 's':
                    if ($value != '') {
                        $parameters['sort'] = $value;
                    }
                    break;
                case 'o':
                    if ($value != '') {
                        $parameters['order'] = $value;
                    }
                    break;
                case 'p':
                    if ($value != '') {
                        $parameters['page'] = $value;
                    }
                    break;
                case 'f':
                    foreach ($value as $filter) {
                        $filterArray = explode(':', $filter);

                        if ($filterArray[0] != '' && $filterArray[1] != '') {
                            $parameters['filters'][$filterArray[0]] = $filterArray[1];
                            break;
                        }
                    }


            }
        }

        return $parameters;
    }
}
