<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

$db_params = dirname(__FILE__) .'/db_params.php';
require_once($db_params);

$backend = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..';
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => '后台',

    // preloading 'log' component
    'preload' => array('log'),

    'viewPath' => $backend . '/admin_views',
    'controllerPath' => $backend . '/admin_controllers',

    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.biz.*',
    ),

    'defaultController' => 'site',
    'modules' => array(
        // uncomment the following to enable the Gii tool

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '111111',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),

    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'loginUrl'=>array('site/login'),
        ),
        
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
            'itemTable' => 'authitem',
            'itemChildTable' => 'authitemchild',
            'assignmentTable' => 'authassignment',
        ),

        'db'=>array(
            'connectionString' => "mysql:host=$dbhost;dbname=$dbname",
            'emulatePrepare' => true,
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8',
        ),
        
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            // 'showScriptName' => false,
            // 'urlSuffix' => '.html',
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                // uncomment the following to show log messages on web pages
                /*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
            ),
        ),
        'file_cache' => array(
            'class' => 'system.caching.CFileCache',
        ),
        /**
        * curl
        */
        'curl'=>array(
            'class'=>'application.extensions.curl.Curl',
        ),
        /**
        * 图片处理
        */
        'imagehelper'=>array(
          'class'=>'application.extensions.imagehelper.CImageComponent',
            // GD or ImageMagick
            'driver'=>'GD',
            // ImageMagick setup path
            //'params'=>array('directory'=>'/opt/local/bin'),
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => require(dirname(__FILE__) . '/params.php'),
);
