<?php

/*
|--------------------------------------------------------------------------
| Application Constants
|--------------------------------------------------------------------------
|
| 
|
*/

if(!defined('SAMPLE_ID')) define('SAMPLE_ID', 1);

if(!defined('TAKE_COUNT')) define('TAKE_COUNT', 6);

if(!defined('NO')) define('NO', 0);
if(!defined('YES')) define('YES', 1);

if(!defined('PAID')) define('PAID',1);
if(!defined('UNPAID')) define('UNPAID', 0);


if(!defined('AVAILABLE')) define('AVAILABLE', 1);
if(!defined('NOTAVAILABLE')) define('NOTAVAILABLE', 0);

if(!defined('DATE_AVAILABLE')) define('DATE_AVAILABLE', 1);
if(!defined('DATE_NOTAVAILABLE')) define('DATE_NOTAVAILABLE', 0);

if(!defined('DEVICE_ANDROID')) define('DEVICE_ANDROID', 'android');
if(!defined('DEVICE_IOS')) define('DEVICE_IOS', 'ios');
if(!defined('DEVICE_WEB')) define('DEVICE_WEB', 'web');

if(!defined('APPROVED')) define('APPROVED', 1);
if(!defined('DECLINED')) define('DECLINED', 0);

if(!defined('DEFAULT_TRUE')) define('DEFAULT_TRUE', true);
if(!defined('DEFAULT_FALSE')) define('DEFAULT_FALSE', false);

if(!defined('ADMIN')) define('ADMIN', 'admin');
if(!defined('USER')) define('USER', 'user');
if(!defined('PROVIDER')) define('PROVIDER', 'provider');


if(!defined('COD')) define('COD',   'COD');
if(!defined('PAYPAL')) define('PAYPAL', 'PAYPAL');
if(!defined('CARD')) define('CARD',  'CARD');

if(!defined('STRIPE_MODE_LIVE')) define('STRIPE_MODE_LIVE',  'live');
if(!defined('STRIPE_MODE_SANDBOX')) define('STRIPE_MODE_SANDBOX',  'sandbox');

//////// USERS

if(!defined('USER_TYPE_NORMAL')) define('USER_TYPE_NORMAL', 0);
if(!defined('USER_TYPE_PAID')) define('USER_TYPE_PAID', 1);

if(!defined('USER_PENDING')) define('USER_PENDING', 0);
if(!defined('USER_APPROVED')) define('USER_APPROVED', 1);
if(!defined('USER_DECLINED')) define('USER_DECLINED', 2);

if(!defined('USER_EMAIL_NOT_VERIFIED')) define('USER_EMAIL_NOT_VERIFIED', 0);
if(!defined('USER_EMAIL_VERIFIED')) define('USER_EMAIL_VERIFIED', 1);

if(!defined('USER_STEP_WELCOME')) define('USER_STEP_WELCOME', 0);
if(!defined('USER_STEP_COMPLETED')) define('USER_STEP_COMPLETED', 1);

//////// PROVIDERs

if(!defined('PROVIDER_TYPE_NORMAL')) define('PROVIDER_TYPE_NORMAL', 0);
if(!defined('PROVIDER_TYPE_PAID')) define('PROVIDER_TYPE_PAID', 1);

if(!defined('PROVIDER_PENDING')) define('PROVIDER_PENDING', 0);
if(!defined('PROVIDER_APPROVED')) define('PROVIDER_APPROVED', 1);
if(!defined('PROVIDER_DECLINED')) define('PROVIDER_DECLINED', 2);

if(!defined('PROVIDER_EMAIL_NOT_VERIFIED')) define('PROVIDER_EMAIL_NOT_VERIFIED', 0);
if(!defined('PROVIDER_EMAIL_VERIFIED')) define('PROVIDER_EMAIL_VERIFIED', 1);

if(!defined('PROVIDER_STEP_WELCOME')) define('PROVIDER_STEP_WELCOME', 0);
if(!defined('PROVIDER_STEP_COMPLETED')) define('PROVIDER_STEP_COMPLETED', 1);

//////// USERS END

/***** ADMIN CONTROLS KEYS ********/

if(!defined('ADMIN_CONTROL_ENABLED')) define("ADMIN_CONTROL_ENABLED", 1);

if(!defined('ADMIN_CONTROL_DISABLED')) define("ADMIN_CONTROL_DISABLED", 0);

if(!defined('NO_DEVICE_TOKEN')) define("NO_DEVICE_TOKEN", "NO_DEVICE_TOKEN");

// Notification settings

if(!defined('EMAIL_NOTIFICATION')) define('EMAIL_NOTIFICATION', 'email');

if(!defined('PUSH_NOTIFICATION')) define('PUSH_NOTIFICATION', 'push');


// HOST related constants 

//  The provider will have control over the host ( show or hide )

if(!defined('HOST_OWNER_PUBLISHED')) define('HOST_OWNER_PUBLISHED' , 1);

if(!defined('HOST_OWNER_UNPUBLISHED')) define('HOST_OWNER_UNPUBLISHED' , 0);

// The admin will have control on the host display ( approve or decline)

if(!defined('ADMIN_HOST_APPROVED')) define('ADMIN_HOST_APPROVED' , 1);

if(!defined('ADMIN_HOST_PENDING')) define('ADMIN_HOST_PENDING' , 0);

// On new host listed, the admin needs to verify the host

if(!defined('ADMIN_HOST_VERIFY_PENDING')) define('ADMIN_HOST_VERIFY_PENDING' , 0);

if(!defined('ADMIN_HOST_VERIFIED')) define('ADMIN_HOST_VERIFIED' , 1);

if(!defined('ADMIN_HOST_VERIFY_DECLINED')) define('ADMIN_HOST_VERIFY_DECLINED' , 2);



// These constants are used identify the home page api types http://prntscr.com/mahza1

// Home page data start

if(!defined('API_PAGE_TYPE_HOME')) define('API_PAGE_TYPE_HOME', 'HOME');

if(!defined('API_PAGE_TYPE_CATEGORY')) define('API_PAGE_TYPE_CATEGORY', "CATEGORY");

if(!defined('API_PAGE_TYPE_SUB_CATEGORY')) define('API_PAGE_TYPE_SUB_CATEGORY', "SUB_CATEGORY");

if(!defined('API_PAGE_TYPE_LOCATION')) define('API_PAGE_TYPE_LOCATION', "LOCATION");

// Home page data end

// Single data start

if(!defined('API_PAGE_TYPE_SEE_ALL')) define('API_PAGE_TYPE_SEE_ALL', "SEE_ALL");

if(!defined('API_PAGE_TYPE_TOP_RATED')) define('API_PAGE_TYPE_TOP_RATED', "TOP_RATED");

if(!defined('API_PAGE_TYPE_WISHLIST')) define('API_PAGE_TYPE_WISHLIST', "WISHLIST");

if(!defined('API_PAGE_TYPE_RECENT_UPLOADED')) define('API_PAGE_TYPE_RECENT_UPLOADED', "RECENT_UPLOADED");

if(!defined('API_PAGE_TYPE_SUGGESTIONS')) define('API_PAGE_TYPE_SUGGESTIONS', "SUGGESTIONS");

// Single data end



// Home page types 

if(!defined('URL_TYPE_CATEGORY')) define('URL_TYPE_CATEGORY' , 1);

if(!defined('URL_TYPE_SUB_CATEGORY')) define('URL_TYPE_SUB_CATEGORY' , 2);

if(!defined('URL_TYPE_LOCATION')) define('URL_TYPE_LOCATION' , 3);

if(!defined('URL_TYPE_TOP_RATED')) define('URL_TYPE_TOP_RATED' , 4);

if(!defined('URL_TYPE_WISHLIST')) define('URL_TYPE_WISHLIST' , 5);

if(!defined('URL_TYPE_RECENT_UPLOADED')) define('URL_TYPE_RECENT_UPLOADED' , 6);

if(!defined('URL_TYPE_SUGGESTIONS')) define('URL_TYPE_SUGGESTIONS' , 7);

// android view for hosts list

if(!defined('SECTION_TYPE_HORIZONTAL')) define('SECTION_TYPE_HORIZONTAL' , 'HORIZONTAL');

if(!defined('SECTION_TYPE_VERTICAL')) define('SECTION_TYPE_VERTICAL' , 'VERTICAL');

if(!defined('SECTION_TYPE_GRID')) define('SECTION_TYPE_GRID' , 'GRID');


// Host types 

if(!defined('HOST_PRIVATE')) define('HOST_PRIVATE' , 'Private Room');

if(!defined('HOST_ENTIRE')) define('HOST_ENTIRE' , 'Entire Room');

if(!defined('HOST_SHARED')) define('HOST_SHARED' , 'Shared Room');


if(!defined('BOOKING_INITIATE')) define('BOOKING_INITIATE' , 0);

if(!defined('BOOKING_ONPROGRESS')) define('BOOKING_ONPROGRESS' , 1);

if(!defined('BOOKING_WAITING_FOR_PAYMENT')) define('BOOKING_WAITING_FOR_PAYMENT' , 2);

if(!defined('BOOKING_DONE_BY_USER')) define('BOOKING_DONE_BY_USER' , 3);

if(!defined('BOOKING_CANCELLED_BY_USER')) define('BOOKING_CANCELLED_BY_USER' , 4);

if(!defined('BOOKING_CANCELLED_BY_PROVIDER')) define('BOOKING_CANCELLED_BY_PROVIDER' , 5);

if(!defined('BOOKING_COMPLETED')) define('BOOKING_COMPLETED' , 6);

if(!defined('BOOKING_REFUND_INITIATED')) define('BOOKING_REFUND_INITIATED' , 7);

if(!defined('BOOKING_CHECKIN')) define('BOOKING_CHECKIN' , 8);

if(!defined('BOOKING_CHECKOUT')) define('BOOKING_CHECKOUT' , 9);

if(!defined('BOOKING_REVIEW_DONE')) define('BOOKING_REVIEW_DONE' , 10);


if(!defined('SEARCH_OPTION_DATE')) define('SEARCH_OPTION_DATE' , 1);

if(!defined('SEARCH_OPTION_GUEST')) define('SEARCH_OPTION_GUEST' , 2);

if(!defined('SEARCH_OPTION_HOST_TYPE')) define('SEARCH_OPTION_HOST_TYPE' , 3);

if(!defined('SEARCH_OPTION_PRICE')) define('SEARCH_OPTION_PRICE' , 4);

if(!defined('SEARCH_OPTION_OTHER')) define('SEARCH_OPTION_OTHER' , 5);

if(!defined('SEARCH_OPTION_ROOMS_BEDS')) define('SEARCH_OPTION_ROOMS_BEDS' , 6);

if(!defined('SEARCH_OPTION_SUB_CATEGORY')) define('SEARCH_OPTION_SUB_CATEGORY' , 7);

if(!defined('SEARCH_OPTION_AMENTIES')) define('SEARCH_OPTION_AMENTIES' , 8);


// Bell notification status

if(!defined('BELL_NOTIFICATION_STATUS_UNREAD')) define('BELL_NOTIFICATION_STATUS_UNREAD', 1);

if(!defined('BELL_NOTIFICATION_STATUS_READ')) define('BELL_NOTIFICATION_STATUS_READ', 2);

// Bell notification redirection type

if(!defined('BELL_NOTIFICATION_REDIRECT_HOME')) define('BELL_NOTIFICATION_REDIRECT_HOME', 1);

if(!defined('BELL_NOTIFICATION_REDIRECT_HOST_VIEW')) define('BELL_NOTIFICATION_REDIRECT_HOST_VIEW', 2);

if(!defined('BELL_NOTIFICATION_REDIRECT_BOOKINGS')) define('BELL_NOTIFICATION_REDIRECT_BOOKINGS', 3);

if(!defined('BELL_NOTIFICATION_REDIRECT_BOOKING_VIEW')) define('BELL_NOTIFICATION_REDIRECT_BOOKING_VIEW', 4);

if(!defined('BELL_NOTIFICATION_REDIRECT_CHAT')) define('BELL_NOTIFICATION_REDIRECT_CHAT', 5);


// User bell notification status

if(!defined('NOTIFICATION_BOOKING_ACCECPTED_BY_PROVIDER')) define('NOTIFICATION_BOOKING_ACCECPTED_BY_PROVIDER', 1);

if(!defined('NOTIFICATION_BOOKING_REJECTED_BY_PROVIDER')) define('NOTIFICATION_BOOKING_REJECTED_BY_PROVIDER', 2);

if(!defined('NOTIFICATION_BOOKING_CANCELED_BY_PROVIDER')) define('NOTIFICATION_BOOKING_CANCELED_BY_PROVIDER', 3);

if(!defined('NOTIFICATION_BOOKING_STARTED')) define('NOTIFICATION_BOOKING_STARTED', 4);

if(!defined('NOTIFICATION_BOOKING_COMPLETED')) define('NOTIFICATION_BOOKING_COMPLETED', 5);

if(!defined('NOTIFICATION_BOOKING_REVIEWED_BY_PROVIDER')) define('NOTIFICATION_BOOKING_REVIEWED_BY_PROVIDER', 6);

if(!defined('NOTIFICATION_NEW_MESSAGE_FROM_PROVIDER')) define('NOTIFICATION_NEW_MESSAGE_FROM_PROVIDER', 7);

if(!defined('NOTIFICATION_NEW_HOST_UPLOADED')) define('NOTIFICATION_NEW_HOST_UPLOADED', 8);

// Provider bell notification status

if(!defined('NOTIFICATION_BOOKING_NEW_FROM_USER')) define('NOTIFICATION_BOOKING_NEW_FROM_USER', 9);

if(!defined('NOTIFICATION_BOOKING_CANCELED_BY_USER')) define('NOTIFICATION_BOOKING_CANCELED_BY_USER', 10);

if(!defined('NOTIFICATION_BOOKING_REVIEWED_BY_USER')) define('NOTIFICATION_BOOKING_REVIEWED_BY_USER', 11);

if(!defined('NOTIFICATION_NEW_MESSAGE_FROM_USER')) define('NOTIFICATION_NEW_MESSAGE_FROM_USER', 12);

if(!defined('NOTIFICATION_HOST_APPROVED')) define('NOTIFICATION_HOST_APPROVED', 13);

if(!defined('NOTIFICATION_HOST_DECLINED')) define('NOTIFICATION_HOST_DECLINED', 14);

if(!defined('NOTIFICATION_HOST_VERIFIED')) define('NOTIFICATION_HOST_VERIFIED', 15);


if(!defined("DROPDOWN")) define("DROPDOWN", 'dropdown');

if(!defined('CHECKBOX')) define('CHECKBOX', 'checkbox');

if(!defined('RADIO')) define('RADIO', 'radio');

if(!defined('SPINNER')) define('SPINNER', 'spinner');

if(!defined('SPINNER_CALL_SUB_CATEGORY')) define('SPINNER_CALL_SUB_CATEGORY', 'call_sub_category_api');

if(!defined('SWITCH')) define('SWITCH', 'switch');

if(!defined('RANGE')) define('RANGE', 'range');

if(!defined('AVAILABILITY_CALENDAR')) define('AVAILABILITY_CALENDAR', 'availability_calendar');


if(!defined('ABOUT_HOST_SPACE')) define('ABOUT_HOST_SPACE', 'about_host_space');

if(!defined('REVIEW')) define('REVIEW', 'REVIEW');

if(!defined('TEXTAREA')) define('TEXTAREA', 'textarea');

if(!defined('INPUT')) define('INPUT', 'input');

if(!defined('INPUT_NUMBER')) define('INPUT_NUMBER', 'number');

if(!defined('INPUT_TEXT')) define('INPUT_TEXT', 'text');

if(!defined('INPUT_TEXTAREA')) define('INPUT_TEXTAREA', 'textarea');

if(!defined('INPUT_GOOGLE_PLACE_SEARCH')) define('INPUT_GOOGLE_PLACE_SEARCH', 'input_place_search');

if(!defined('MAP_VIEW')) define('MAP_VIEW', 'map_view');

if(!defined('DATE')) define('DATE', 'date');

if(!defined('INCREMENT_DECREMENT')) define('INCREMENT_DECREMENT', 'increment');

if(!defined('UPLOAD')) define('UPLOAD', 'upload');

if(!defined('UPLOAD_SINGLE')) define('UPLOAD_SINGLE', 'single');

if(!defined('UPLOAD_MULTIPLE')) define('UPLOAD_MULTIPLE', 'multiple');


if(!defined('PLAN_TYPE_MONTH')) define('PLAN_TYPE_MONTH', 'month');

if(!defined('PLAN_TYPE_DAY')) define('PLAN_TYPE_DAY', 'day');

if(!defined('PLAN_TYPE_YEAR')) define('PLAN_TYPE_YEAR', 'year');


// HOST add stpes

if(!defined('HOST_STEPS')) define('HOST_STEPS', 'STEPS');

if(!defined('HOST_STEP_0')) define('HOST_STEP_0', '0_INITIAL');

if(!defined('HOST_STEP_1')) define('HOST_STEP_1', '1_PROPERTY');

if(!defined('HOST_STEP_2')) define('HOST_STEP_2', '2_LOCATION');

if(!defined('HOST_STEP_3')) define('HOST_STEP_3', '3_AMENTIES');

if(!defined('HOST_STEP_4')) define('HOST_STEP_4', '4_BASICS');

if(!defined('HOST_STEP_5')) define('HOST_STEP_5', '5_OTHER_QUESTIONS');

if(!defined('HOST_STEP_6')) define('HOST_STEP_6', '6_PRICING');

if(!defined('HOST_STEP_7')) define('HOST_STEP_7', '7_AVAILABILITY');

if(!defined('HOST_STEP_8')) define('HOST_STEP_8', '8_CONFIRMATION_AND_PREVIEW');

if(!defined('HOST_STEP_COMPLETE')) define('HOST_STEP_COMPLETE', 'COMPLETE');


if(!defined('BATHROOM_TYPE_PRIVATE')) define('BATHROOM_TYPE_PRIVATE', 'private');

if(!defined('BATHROOM_TYPE_SHARED')) define('BATHROOM_TYPE_SHARED', 'shared');

if(!defined('CATEGORIES')) define('CATEGORIES', 'categories');

if(!defined('SUB_CATEGORIES')) define('SUB_CATEGORIES', 'sub_categories');

if (!defined('PAID_STATUS')) define('PAID_STATUS', 1);


if (!defined('QUESTION_TYPE_AMENTIES')) define('QUESTION_TYPE_AMENTIES', 'amenties');


