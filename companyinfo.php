<?php
function companyshortname()
{
    return "ServeCorp";
}

function companyname()
{
    return "ServeCorp pacific Inc.";
}

function companyaddress()
{
    return "Unit KMC, 9th Floor V Corporate Centre, 125 LP Leviste St., Salcedo Village, Makati City, Philippines";
}

function companycontact()
{
    return "+63.02.4858340";
}


function companymobile()
{
    return "none";
}

function companyfax()
{
    return "none";
}

function companyemail()
{
    return "marketing@servecorppacific.com";
}

function companywebsite()
{
    return "www.servecorppacific.com";
}

function companymotto()
{
    return "none";
}

function currency()
{
    return "USD";
}

function Services($services)
{
    switch($services)
    {
        case "payroll-services": return "Payroll Services"; break;
        case "benefits-and-compensation": return 'Benefits and Compensation'; break;
        case "hr-policy-and-procedure": return "HR Policy and Procedure"; break;
        case "web-design-and-development": return "Web  Design & Development"; break;
        case "mobile-app-development": return "Mobile App Development"; break;
        case "accounting": return "Accounting"; break;
        case "social-media-marketing": return "Social Media Marketing"; break;
        case "customer-support-voice": return "Customer Support (Voice & Non-Voice)"; break;
        case "customer-support-non-voice": return "Customer Support (Voice & Non-Voice)"; break;
    }
}

function ServicesCategory($category)
{
    switch($category)
    {
        case "human-resource-solutions": return "Human Resource Solutions"; break;
        case "it-services": return 'IT Services'; break;
        case "finance": return "Finance"; break;
        case "online-marketing": return "Online Marketing"; break;
        case "call-center-services": return "Call Center Services"; break;
    }
}



?>