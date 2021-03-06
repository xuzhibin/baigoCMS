<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

/*-------------基类-------------*/
class CLASS_BASE {

    public $config; //配置

    function __construct() { //构造函数
        $this->getUi(); //获取界面类型
        $this->getLang(); //获取当前语言
        $this->setTimezone(); //设置时区
    }


    /**
     * getLang function.
     *
     * @access public
     * @return void
     */
    function getLang() {
        //print_r("test");
        if (defined("BG_SWITCH_LANG") && BG_SWITCH_LANG == 1) { //语言开关为开
            $str_lang = fn_getSafe(fn_get("lang"), "txt", "");

            if ($str_lang) { //查询串指定
                $_str_return = $str_lang;
            } else {
                /*if (fn_cookie("cookie_lang")) { //cookie 指定
                    $_str_return = fn_cookie("cookie_lang");
                } else { //系统识别*/
                    if (fn_server("HTTP_ACCEPT_LANGUAGE")) {
                        $_str_agentUser = fn_server("HTTP_ACCEPT_LANGUAGE");
                        if (stristr($_str_agentUser, "zh")) {
                            $_str_return = BG_DEFAULT_LANG; //客户端是中文
                        } else {
                            $_str_return = "en"; //客户端是英文
                        }
                    } else {
                        $_str_return = BG_DEFAULT_LANG; //客户端是中文
                    }

                //}
            }
        } else { //语言开关为关
            $_str_return = BG_DEFAULT_LANG; //默认语言
        }

        $this->config["lang"] = $_str_return;
    }


    /**
     * getUi function.
     *
     * @access public
     * @return void
     */
    function getUi() {
        if (BG_SWITCH_UI) { //界面开关为开
            $str_ui = fn_getSafe(fn_get("ui"), "txt", "");

            if ($str_ui) { //查询串指定
                $_str_return = $str_ui;
            } else {
                /*if (fn_cookie("cookie_ui")) { //cookie 指定
                    $_str_return = fn_cookie("cookie_ui");
                } else { //系统识别*/
                    $_str_return = BG_DEFAULT_UI; //客户端是 pc
                //}
            }
        } else { //界面开关为关
            $_str_return = BG_DEFAULT_UI; //默认界面
        }
        $this->config["ui"] = $_str_return;
    }


    /**
     * setTimezone function.
     *
     * @access public
     * @return void
     */
    function setTimezone() {
        if(!defined("BG_SITE_TIMEZONE")) {
            define("BG_SITE_TIMEZONE", "Asia/Shanghai");
        }

        date_default_timezone_set(BG_SITE_TIMEZONE);
    }
}