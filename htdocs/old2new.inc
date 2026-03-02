<?php

if (!function_exists('each')) {
    function each(&$array)
    {
        if (!is_array($array)) {
            return false;
        }

        $key = key($array);
        if ($key === null) {
            return false;
        }

        $value = current($array);
        next($array);

        return array(
            1 => $value,
            'value' => $value,
            0 => $key,
            'key' => $key,
        );
    }
}

if (!function_exists('ereg')) {
    function ereg($pattern, $string, &$regs = null)
    {
        $delimiter = '/';
        $quotedPattern = str_replace($delimiter, '\\' . $delimiter, $pattern);
        $result = preg_match($delimiter . $quotedPattern . $delimiter, $string, $matches);
        if ($result && is_array($regs)) {
            $regs = $matches;
        } elseif ($result) {
            $regs = $matches;
        }
        return $result;
    }
}

if (!function_exists('eregi')) {
    function eregi($pattern, $string, &$regs = null)
    {
        $delimiter = '/';
        $quotedPattern = str_replace($delimiter, '\\' . $delimiter, $pattern);
        $result = preg_match($delimiter . $quotedPattern . $delimiter . 'i', $string, $matches);
        if ($result && is_array($regs)) {
            $regs = $matches;
        } elseif ($result) {
            $regs = $matches;
        }
        return $result;
    }
}

if (!function_exists('ereg_replace')) {
    function ereg_replace($pattern, $replacement, $string)
    {
        $delimiter = '/';
        $quotedPattern = str_replace($delimiter, '\\' . $delimiter, $pattern);
        return preg_replace($delimiter . $quotedPattern . $delimiter, $replacement, $string);
    }
}

if (!function_exists('eregi_replace')) {
    function eregi_replace($pattern, $replacement, $string)
    {
        $delimiter = '/';
        $quotedPattern = str_replace($delimiter, '\\' . $delimiter, $pattern);
        return preg_replace($delimiter . $quotedPattern . $delimiter . 'i', $replacement, $string);
    }
}

if (!function_exists('split')) {
    function split($pattern, $string, $limit = -1)
    {
        $delimiter = '/';
        $quotedPattern = str_replace($delimiter, '\\' . $delimiter, $pattern);
        return preg_split($delimiter . $quotedPattern . $delimiter, $string, $limit);
    }
}

if (!function_exists('mysql_connect')) {
    if (!defined('MYSQL_ASSOC')) {
        define('MYSQL_ASSOC', 1);
    }
    if (!defined('MYSQL_NUM')) {
        define('MYSQL_NUM', 2);
    }
    if (!defined('MYSQL_BOTH')) {
        define('MYSQL_BOTH', 3);
    }

    $GLOBALS['__mysql_compat_link'] = null;

    function mysql_connect($server = null, $username = null, $password = null, $new_link = false, $client_flags = 0)
    {
        if (!function_exists('mysqli_connect')) {
            return false;
        }
        $connection = @mysqli_connect($server, $username, $password);
        if ($connection) {
            $GLOBALS['__mysql_compat_link'] = $connection;
        }
        return $connection;
    }

    function mysql_pconnect($server = null, $username = null, $password = null, $client_flags = 0)
    {
        return mysql_connect($server, $username, $password, true, $client_flags);
    }

    function mysql_close($link_identifier = null)
    {
        if (!function_exists('mysqli_close')) {
            return false;
        }
        if (!$link_identifier) {
            $link_identifier = isset($GLOBALS['__mysql_compat_link']) ? $GLOBALS['__mysql_compat_link'] : null;
        }
        if (!$link_identifier) {
            return true;
        }
        return mysqli_close($link_identifier);
    }

    function mysql_select_db($database_name, $link_identifier = null)
    {
        if (!function_exists('mysqli_select_db')) {
            return false;
        }
        if (!$link_identifier) {
            $link_identifier = isset($GLOBALS['__mysql_compat_link']) ? $GLOBALS['__mysql_compat_link'] : null;
        }
        if (!$link_identifier) {
            return false;
        }
        return mysqli_select_db($link_identifier, $database_name);
    }

    function mysql_query($query, $link_identifier = null)
    {
        if (!function_exists('mysqli_query')) {
            return false;
        }
        if (!$link_identifier) {
            $link_identifier = isset($GLOBALS['__mysql_compat_link']) ? $GLOBALS['__mysql_compat_link'] : null;
        }
        if (!$link_identifier) {
            return false;
        }
        return mysqli_query($link_identifier, $query);
    }

    function mysql_num_rows($result)
    {
        if (!function_exists('mysqli_num_rows')) {
            return 0;
        }
        return mysqli_num_rows($result);
    }

    function mysql_fetch_row($result)
    {
        if (!function_exists('mysqli_fetch_row')) {
            return false;
        }
        return mysqli_fetch_row($result);
    }

    function mysql_fetch_assoc($result)
    {
        if (!function_exists('mysqli_fetch_assoc')) {
            return false;
        }
        return mysqli_fetch_assoc($result);
    }

    function mysql_fetch_array($result, $result_type = MYSQL_BOTH)
    {
        if (!function_exists('mysqli_fetch_array')) {
            return false;
        }
        if ($result_type === MYSQL_ASSOC) {
            return mysqli_fetch_array($result, MYSQLI_ASSOC);
        }
        if ($result_type === MYSQL_NUM) {
            return mysqli_fetch_array($result, MYSQLI_NUM);
        }
        return mysqli_fetch_array($result, MYSQLI_BOTH);
    }

    function mysql_free_result($result)
    {
        if (!function_exists('mysqli_free_result')) {
            return false;
        }
        return mysqli_free_result($result);
    }

    function mysql_insert_id($link_identifier = null)
    {
        if (!function_exists('mysqli_insert_id')) {
            return 0;
        }
        if (!$link_identifier) {
            $link_identifier = isset($GLOBALS['__mysql_compat_link']) ? $GLOBALS['__mysql_compat_link'] : null;
        }
        if (!$link_identifier) {
            return 0;
        }
        return mysqli_insert_id($link_identifier);
    }

    function mysql_affected_rows($link_identifier = null)
    {
        if (!function_exists('mysqli_affected_rows')) {
            return 0;
        }
        if (!$link_identifier) {
            $link_identifier = isset($GLOBALS['__mysql_compat_link']) ? $GLOBALS['__mysql_compat_link'] : null;
        }
        if (!$link_identifier) {
            return 0;
        }
        return mysqli_affected_rows($link_identifier);
    }

    function mysql_errno($link_identifier = null)
    {
        if (!function_exists('mysqli_errno')) {
            return 0;
        }
        if (!$link_identifier) {
            $link_identifier = isset($GLOBALS['__mysql_compat_link']) ? $GLOBALS['__mysql_compat_link'] : null;
        }
        if (!$link_identifier) {
            return 0;
        }
        return mysqli_errno($link_identifier);
    }

    function mysql_error($link_identifier = null)
    {
        if (!function_exists('mysqli_error')) {
            return '';
        }
        if (!$link_identifier) {
            $link_identifier = isset($GLOBALS['__mysql_compat_link']) ? $GLOBALS['__mysql_compat_link'] : null;
        }
        if (!$link_identifier) {
            return '';
        }
        return mysqli_error($link_identifier);
    }

    function mysql_escape_string($unescaped_string)
    {
        return addslashes($unescaped_string);
    }

    function mysql_real_escape_string($unescaped_string, $link_identifier = null)
    {
        if (!function_exists('mysqli_real_escape_string')) {
            return addslashes($unescaped_string);
        }
        if (!$link_identifier) {
            return addslashes($unescaped_string);
        }
        return mysqli_real_escape_string($link_identifier, $unescaped_string);
    }
}

// Codigo para compatibilizar versoes anteriores do PHP
// que consideravam qualquer coisa que vinha do usuario
// como variavel globa (versoes anteriores ao PHP 4.1)
/*
Em PHP anterior a 4.1 temos:
$PHP_SELF...........The filename of the currently executing script, relative to the document root.
                    If PHP is running as a command-line processor, this variable is not available. 
$HTTP_COOKIE_VARS...An associative array of variables passed to the current script via HTTP cookies. 
$HTTP_GET_VARS......An associative array of variables passed to the current script via the HTTP GET method. 
$HTTP_POST_VARS.....An associative array of variables passed to the current script via the HTTP POST method. 
$HTTP_POST_FILES....An associative array of variables containing information about files uploaded via the
                    HTTP POST method. See POST method uploads for information on the contents of $HTTP_POST_FILES. 
$HTTP_POST_FILES....is available only in PHP 4.0.0 and later. 
$HTTP_ENV_VARS......An associative array of variables passed to the current script via the parent environment. 
$HTTP_SERVER_VARS...An associative array of variables passed to the current script from the HTTP server.
                    These variables are analogous to the Apache variables described above. 
Em PHP 4.2 temos:
$_GET.......contains form variables sent through GET 
$_POST......contains form variables sent through POST 
$_COOKIE....contains HTTP cookie variables 
$_SERVER....contains server variables (e.g., REMOTE_ADDR) 
$_ENV.......contains the environment variables 
$_REQUEST...a merge of the GET variables, POST variables and Cookie
            variables. In other words - all the information that is
			coming from the user, and that from a security point of
			view, cannot be trusted. 
$_SESSION...contains HTTP variables registered by the session module
$_POST......Variables provided to the script via HTTP POST. Analogous
            to the old $HTTP_POST_VARS array (which is still available,
			but deprecated). 

*/
$old2newArray = array("HTTP_POST_VARS"    => "_POST",
                      "HTTP_GET_VARS"     => "_GET",
					  "HTTP_COOKIE_VARS"  => "_COOKIE",
					  "HTTP_SERVER_VARS"  => "_SERVER",
					  "HTTP_ENV_VARS"     => "_ENV",
					  "HTTP_SESSION_VARS" => "_SESSION",
					  "HTTP_POST_FILES " => "_REQUEST");
foreach ($old2newArray as $old => $new) {
	if (!isset($$old) && isset($$new)) {
		$$old = $$new;
	}
	if (isset($$old) && is_array($$old)) {
		foreach ($$old as $key => $value) {
			$GLOBALS[$key] = $value;
		}
	}
}

?>
