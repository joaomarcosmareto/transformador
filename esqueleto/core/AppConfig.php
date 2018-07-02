<?php

namespace core;

class AppConfig {

    static $DB_TYPE = "Mongo";

    static $SERVER_NAME = "https://projeto.echo.com:4430";
    static $AUDIENCE_NAME = "EasyTreino Users";
    static $SECRET_KEY = "!U)oO+{0 +FF>]O5WI]<U~SawkGaZ.,|";

    static $URL_BASE = "localhost:8088/wm";//"easytreino.com" ou "easytreino.com/wmteste"
    static $URL_TESTE_BASE = "localhost/wm";

    static $I18N_REPLACE = "&%&";

    //EMAILS RELATED
    static $SENDING_MAIL_RUNNING = false;
    static $ENVIAR_EMAILS = true;

    // static $EMAIL_HOST = 'localhost';
    // static $EMAIL_PORT = '25';
    // static $EMAIL_USERNAME = '';
    // static $EMAIL_PASSWORD = '';

    static $EMAIL_HOST = 'smtp.gmail.com';
    static $EMAIL_PORT = '587';
    static $EMAIL_USERNAME = 'maretoinformatica@gmail.com';
    static $EMAIL_PASSWORD = '44eHdiWylTg6SK';
    //FIM EMAILS RELATED

    static $AMBIENTE = "DEV";
    // static $AMBIENTE = "PROD";

    static $ADMIN_MAIL = 'workoutmobilemail@gmail.com';
    static $ADMIN_ID = '';

    static $LogarTempoExecucao = true;
    static $LogarQuery = true;
    static $LogarTempoExecucaoQuery = true;
    static $LogarRetorno = false;

    static function getDirBase()
    {
        return dirname ( realpath ( __FILE__ ) ).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR;
    }

    static function getDirCore()
    {
        return dirname ( realpath ( __FILE__ ) ).DIRECTORY_SEPARATOR;
    }

    static function getDirLog()
    {
        return dirname ( realpath ( __FILE__ ) ).DIRECTORY_SEPARATOR."log".DIRECTORY_SEPARATOR;
    }

    static function getBaseURL()
    {
        return "http://".AppConfig::$URL_BASE;
    }

    static function getImgPath()
    {
        return AppConfig::getDirBase()."imgs".DIRECTORY_SEPARATOR;
    }

    static function getImgNegocioPath(string $negocio_id)
    {
        return AppConfig::getDirBase().DIRECTORY_SEPARATOR."imgs".DIRECTORY_SEPARATOR.$negocio_id.DIRECTORY_SEPARATOR;
    }

    static function getImgCapaPath()
    {
        return AppConfig::getDirBase().DIRECTORY_SEPARATOR."imgs".DIRECTORY_SEPARATOR;
    }

    static function getImgURL($forceHttp = false)
    {
        //GULP_REPLACE
        return "http://".AppConfig::$URL_BASE."/imgs/";
    }

    static function getTestBaseURL()
    {
        return AppConfig::$URL_TESTE_BASE;
    }
}