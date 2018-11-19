<?php
// error_reporting(0);
error_reporting( error_reporting() & ~E_NOTICE );
include_once("./timezone.php");

function get_dbname()
{
    return "servecorpdb";
}


function get_dbserver()
{
    return "localhost";
}


function get_dbpassword()
{
    return "";
}


function get_dbuser()
{
    return "root";
}






function get_company()
{
    return "Servecorp Pacific Inc.";
}
function get_company_first()
{
    return "ServeCorp";
}
function get_company_last()
{
    return "Pacific Inc.";
}
function get_company_address()
{
    return "Unit KMC, 9th Floor V Corporate Centre, 125 LP Leviste St., Salcedo Village, Makati City, Philippines";
}
function get_company_phone()
{
    return "+63.02.4858340";
}

function get_company_tagline()
{
    return "";
}

function get_company_website()
{
    return "www.servecorppacific.com";
}

function get_company_logo()
{
    return "servecorplogo.jpg";
}


function setpasswordagain()
{ 
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';
    $random_string_length=6;
    for ($i = 0; $i < $random_string_length; $i++)
    {    
        $string .= $characters[rand(0, strlen($characters) - 1)];
    }
   
    return $string;
}


function GetDateStarted()
{
    return "2016-06-01";
}


?>