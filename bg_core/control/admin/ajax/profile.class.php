<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_FUNC . "http.func.php"); //载入 http
include_once(BG_PATH_CLASS . "ajax.class.php"); //载入 AJAX 基类
include_once(BG_PATH_CLASS . "sso.class.php");

/*-------------UC 类-------------*/
class AJAX_PROFILE {

    private $adminLogged;
    private $obj_ajax;
    private $obj_sso;
    private $mdl_admin;

    function __construct() { //构造函数
        $this->adminLogged    = $GLOBALS["adminLogged"]; //获取已登录信息
        $this->obj_ajax       = new CLASS_AJAX();
        $this->obj_ajax->chk_install();
        $this->obj_sso        = new CLASS_SSO();
        $this->mdl_admin      = new MODEL_ADMIN(); //设置管理员对象

        if ($this->adminLogged["alert"] != "y020102") { //未登录，抛出错误信息
            $this->obj_ajax->halt_alert($this->adminLogged["alert"]);
        }
    }


    /**
     * ajax_my function.
     *
     * @access public
     * @return void
     */
    function ajax_info() {
        if (isset($this->adminLogged["admin_allow_profile"]["info"])) {
            $this->obj_ajax->halt_alert("x020108");
        }

        $_arr_adminProfile = $this->mdl_admin->input_profile();
        if ($_arr_adminProfile["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_adminProfile["alert"]);
        }

        $_arr_ssoEdit     = $this->obj_sso->sso_edit($this->adminLogged["admin_id"], "user_id", "", "", $_arr_adminProfile["admin_mail"], $_arr_adminProfile["admin_nick"]);
        $_arr_adminRow    = $this->mdl_admin->mdl_profile($this->adminLogged["admin_id"]);

        if ($_arr_adminRow["alert"] == "y020103" || $_arr_ssoEdit["alert"] == "y010103") {
            $_str_alert = "y020108";
        } else {
            $_str_alert = $_arr_adminRow["alert"];
        }

        $this->obj_ajax->halt_alert($_str_alert);
    }


    /**
     * ajax_pass function.
     *
     * @access public
     * @return void
     */
    function ajax_pass() {
        if (isset($this->adminLogged["admin_allow_profile"]["pass"])) {
            $this->obj_ajax->halt_alert("x020109");
        }

        $_arr_adminPass = $this->mdl_admin->input_pass();
        if ($_arr_adminPass["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_adminPass["alert"]);
        }

        $_arr_ssoEdit = $this->obj_sso->sso_edit($this->adminLogged["admin_id"], "user_id", $_arr_adminPass["admin_pass"], $_arr_adminPass["admin_pass_new"], "", "", true);

        if ($_arr_ssoEdit["alert"] == "y010103") {
            $_str_alert = "y020109";
        } else {
            $_str_alert = $_arr_ssoEdit["alert"];
        }

        $this->obj_ajax->halt_alert($_str_alert);
    }
}
