<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/17
 * Time: 16:20
 */

namespace core\wen\models;

use mysql_xdevapi\Exception;

class Connect
{

    /**
     * @var string
     */
    protected $driver = 'mysql';

    /**
     * @var string
     */
    protected $host = 'localhost';

    /**
     * @var string
     */
    protected $database = '';

    /**
     * @var string
     */
    protected $username = 'root';

    /**
     * @var string
     */
    protected $password = 'root';

    /**
     * @var string
     */
    protected $port = '3306';

    /**
     * @var string
     */
    protected $charset = 'utf8';

    /**
     * @var mixed|string
     */
    protected $collation = 'utf8_general_ci';

    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * @var string
     */
    protected $schema = '';

    /**
     * @var string
     */
    protected $ssl = '';


    protected $sslOptions = [
        'key' => '',  //规定密钥文件的路径名。
        'cert' => '', //规定认证文件的路径名。
        'ca' => '', //规定认证授权文件的路径名。
        'capath' => NULL,  //规定包含 PEM 格式的可信 SSL CA 认证的目录的路径名。
        'cipher' => NULL, //规定用于 SSL 加密的可用密码列表。
    ];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var null
     */
    protected $socket = null;

    /**
     * @var bool|mixed
     */
    protected $strict = false;

    /**
     * @var mixed|null
     */
    protected $engine = null;

    /**
     * @var null
     */
    protected $conn = null;

    public function __construct($configs = [])
    {
        isset($configs['driver']) && $configs['driver'] && $this->driver = $configs['driver'];
        isset($configs['host']) && $configs['host'] && $this->host = $configs['host'];
        isset($configs['database']) && $configs['database'] && $this->database = $configs['database'];
        isset($configs['username']) && $configs['username'] && $this->username = $configs['username'];
        isset($configs['password']) && $configs['password'] && $this->password = $configs['password'];
        isset($configs['port']) && $configs['port'] && $this->port = $configs['port'];
        isset($configs['charset']) && $configs['charset'] && $this->charset = $configs['charset'];
        isset($configs['prefix']) && $configs['prefix'] && $this->prefix = $configs['prefix'];
        isset($configs['schema']) && $configs['schema'] && $this->schema = $configs['schema'];
        isset($configs['ssl']) && $configs['ssl'] && $this->ssl = $configs['ssl'];
        isset($configs['ssl_options']) && $configs['ssl_options'] && $this->sslOptions = $configs['ssl_options'];
        isset($configs['options']) && $configs['options'] && $this->options = $configs['options'];
        isset($configs['unix_socket']) && $configs['unix_socket'] && $this->socket = $configs['unix_socket'];
        isset($configs['collation']) && $configs['collation'] && $this->collation = $configs['collation'];
        isset($configs['strict']) && $configs['strict'] && $this->strict = $configs['strict'];
        isset($configs['engine']) && $configs['engine'] && $this->engine = $configs['engine'];
    }

    /**
     * @return null
     */
    public function handle()
    {
        switch ($this->driver) {
            case 'mysql':
            default:
                $this->mysqlConnect();
        }
        return $this->conn;
    }


    /**
     *
     */
    protected function mysqlConnect()
    {
        if (empty($this->conn)) {
            if ($this->ssl) {
                $this->conn = mysqli_init();
                if (!$this->conn) {
                    throw new Exception('mysqli_init failed');
                }
                $key = isset($this->sslOptions['key']) ? $this->sslOptions['key'] : '';
                $cert = isset($this->sslOptions['cert']) ? $this->sslOptions['cert'] : '';
                $ca = isset($this->sslOptions['ca']) ? $this->sslOptions['ca'] : '';
                $capath = isset($this->sslOptions['capath']) ? $this->sslOptions['capath'] : NULL;
                $cipher = isset($this->sslOptions['cipher']) ? $this->sslOptions['cipher'] : NULL;
                mysqli_ssl_set($this->conn, $key, $cert, $ca, $capath, $cipher);
                if (!mysqli_real_connect($this->conn, $this->host, $this->username, $this->password, $this->database, $this->socket)) {
                    throw new \Exception('MySql connect error: ' . mysqli_connect_error() . '(' . mysqli_connect_errno() . ')');
                }
            } else {
                $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->database, $this->port, $this->socket);
                if ($errno = mysqli_connect_errno()) {
                    throw new Exception('MySql connect error: ' . mysqli_connect_error() . '(' . $errno . ')');
                }
            }
            $this->initMysqlEvn();
        } else {
            mysqli_ping($this->conn);
        }
    }


    protected function initMysqlEvn()
    {
        foreach ($this->options as $option => $value) {
            mysqli_options($this->conn, $option, $value);
        }
        mysqli_set_charset($this->conn, $this->charset);
    }

}