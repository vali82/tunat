<?php
/*return array(
    'db' => array(
        'driver'    => 'PdoMysql',
        'hostname'  => 'localhost',
        'database'  => 'tunat',
        'username'  => 'tunat',
        'password'  => 'tunat!@#$',
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
);*/


$dbParams = array(
    'driver' => 'PdoMysql',
    'hostname' => 'localhost',
    'database' => 'tirboxro_live',
    'username' => 'tirboxro_live',
    'password' => '#cD9Mg8Mka)#',
    // buffer_results - only for mysqli buffered queries, skip for others
    'options' => array('buffer_results' => true)
);

return array(
    'service_manager' => array(
        'factories' => [
            'Zend\Db\Adapter\Adapter' => function ($sm) use ($dbParams) {

                //if (APPLICATION_ENV == 'development') {
                $adapter = new BjyProfiler\Db\Adapter\ProfilingAdapter([
                    'driver' => $dbParams['driver'],
                    'dsn' => 'mysql:dbname=' . $dbParams['database'] . ';host=' . $dbParams['hostname'].';charset=utf8',
                    'database' => $dbParams['database'],
                    'username' => $dbParams['username'],
                    'password' => $dbParams['password'],
                    'hostname' => $dbParams['hostname'],
                ]);

                if (php_sapi_name() == 'cli') {
                    $logger = new Zend\Log\Logger();
                    // write queries profiling info to stdout in CLI mode
                    $writer = new Zend\Log\Writer\Stream('php://output');
                    $logger->addWriter($writer, Zend\Log\Logger::DEBUG);
                    $adapter->setProfiler(new BjyProfiler\Db\Profiler\LoggingProfiler($logger));
                } else {
                    $adapter->setProfiler(new BjyProfiler\Db\Profiler\Profiler());
                }
                if (isset($dbParams['options']) && is_array($dbParams['options'])) {
                    $options = $dbParams['options'];
                } else {
                    $options = [];
                }
                $adapter->injectProfilingStatementPrototype($options);
                return $adapter;
                /* } else {

                    return new Zend\Db\Adapter\Adapter(array(
                        'driver'    => 'pdo',
                        'driver_options' => array(
                               PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                           ),
                        'dsn'       => 'mysql:dbname='.$dbParams['database'].';host='.$dbParams['hostname'],
                        'database'  => $dbParams['database'],
                        'username'  => $dbParams['username'],
                        'password'  => $dbParams['password'],
                        'hostname'  => $dbParams['hostname'],
                    ));
                } */
            },

        ]
    )
);