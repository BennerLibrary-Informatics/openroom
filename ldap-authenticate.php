<?php
function ConnectLdap($name, $password, $settings): bool
{
    $ldapserver = $settings["ldap_baseDN"];
    $name = trim(htmlspecialchars($name));
    $password = trim(htmlspecialchars($password));
    $qc_username = $name;
    $qc_username .= "@olivet.edu";
    $ldap = ldap_connect($ldapserver);
    if (!IsNotNullOrEmptyString($name) && !IsNotNullOrEmptyString($password)) {
        sleep(1);
        if (ldap_bind($ldap, $qc_username, $password)) {
            ldap_close($ldap);
            return true;
        } else {
            ldap_close($ldap);
            return false;
        }
    }
    ldap_close($ldap);
    return false;
}

function ReturnEmailAddress($input_username, $settings)
{
    return ReturnParameter($input_username, "mail", $settings);
}

function ReturnDisplayName($input_username, $settings)
{
    return ReturnParameter($input_username, "displayname", $settings);
}

function ReturnParameter($input_username, $input_parameter, $settings)
{
    return "TRUE";
}

function IsNotNullOrEmptyString($question)
{
    return (!isset($question) || trim($question) === '');
}

// Function to get the client IP address
function get_client_ip()
{
    if (getenv('HTTP_CLIENT_IP'))
        $ipAddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipAddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipAddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipAddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipAddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipAddress = getenv('REMOTE_ADDR');
    else
        $ipAddress = 'UNKNOWN';
    return $ipAddress;
}
