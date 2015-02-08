<?php
/**
 * Created by Niels Gandraß.
 * Copyright (C) 2015
 */

/* Set Timezone */
date_default_timezone_set('Europe/Berlin');

/* Set default charset for foreign servers */
header('Content-Type: text/html; charset=UTF-8');

/* DEFINES for functions */
define(func_core, "func/functions_core.php");
define(func_database, "func/functions_database.php");
define(func_template, "func/functions_template.php");
define(func_sessions, "func/functions_sessions.php");
define(func_bindings, "func/functions_bindings.php");
define(func_devices, "func/functions_devices.php");

/* DEFINES for directories */
define(dir_css, "css/");
define(dir_img, "img/");
define(dir_js, "js/");
define(dir_maintpl, "tpl/");
define(dir_pagetpl, "tpl/pages/");
define(dir_navis, "tpl/navis/");
define(dir_ext, "ext/");

/* DEFINES for base templates */
define(basetpl_header, dir_maintpl."header.tpl.php");
define(basetpl_navi, dir_maintpl."navi.tpl.php");
define(basetpl_footer, dir_maintpl."footer.tpl.php");

/* Basic settings */
$_SETTINGS = array (
    "db_ip" => "127.0.0.1", //Database IP
    "db_port" => 3306, //Database Port
    "db_user" => "root", //Database User
    "db_pass" => "x4f889g", //Database Userpass
    "db_dbase" => "rms" //Default Database's name
);

/* Available templates
 *
 * alias       = GET-Parameter value
 * file        = local template file
 * accessLevel = Required Userlevel (e.g. 0=Unlogged, 1=User, 2=Moderator, 3=Administrator)
 */
$_TEMPLATES = array();
$_TEMPLATES[] = array("alias" => "index", "file" => dir_pagetpl."index.tpl.php", "accessLevel" => 0);
$_TEMPLATES[] = array("alias" => "callsigns", "file" => dir_pagetpl."callsigns.tpl.php", "accessLevel" => 0);
$_TEMPLATES[] = array("alias" => "login", "file" => dir_pagetpl."login.tpl.php", "accessLevel" => 0);
$_TEMPLATES[] = array("alias" => "logout", "file" => dir_pagetpl."logout.tpl.php", "accessLevel" => 0);
$_TEMPLATES[] = array("alias" => "overview", "file" => dir_pagetpl."overview.tpl.php", "accessLevel" => 1);
$_TEMPLATES[] = array("alias" => "devices", "file" => dir_pagetpl."devices.tpl.php", "accessLevel" => 3);
$_TEMPLATES[] = array("alias" => "users", "file" => dir_pagetpl."users.tpl.php", "accessLevel" => 3);
$_TEMPLATES[] = array("alias" => "bindings", "file" => dir_pagetpl."bindings.tpl.php", "accessLevel" => 3);
$_TEMPLATES[] = array("alias" => "add_binding", "file" => dir_pagetpl."add_binding.tpl.php", "accessLevel" => 3);

?>