<?php
namespace Models;

class ORM implements \ArrayAccess
{
    public $host = '127.0.0.1';  //数据库地址
    public $dbname = 'puremvc';   //数据库名
    public $user = 'root';  //数据库用户名
    public $pwd = '';   //数据库密码
    public $port = '3306';  //数据库端口
    public $charset = 'utf8';   //数据库编码
    protected $conn = null;    //数据库链接资源
    protected $alias = [];  //记录全局的语句参数
    protected $sql;    //存储最后一条sql

    public function __construct()
    {
        if( is_null( $this->conn ) ){

            $dsn = "mysql:host=$this->host;dbname=$this->dbname;charset=$this->charset;port=$this->port";
            $this->conn = new \PDO( $dsn, $this->user, $this->pwd );
        }

    }

    //field语句
    public function field( $field )
    {
        if( !is_string( $field ) ){
            throw new \exception("field语句的参数必须为字符串");
        }

        $this->alias['field'] = $field;
        return $this;
    }

    //table语句
    public function table( $table )
    {
        if( !is_string( $table ) ){
            throw new \exception("table语句的参数必须为字符串");
        }

        $this->alias['table'] = $table;
        return $this;
    }

    //where语句
    public function where( $where )
    {
        $this->alias['where'] = '';
        if( is_array( $where ) ){

            foreach( $where as $key=>$vo ){
                $this->alias['where'] .= " `$key`" . ' = ' . $vo . ' and ';
            }
            $this->alias['where'] = rtrim( $this->alias['where'], 'and ' );

        }else if( is_string( $where ) ){

            $this->alias['where'] = $where;
        }else{

            throw new \exception("where语句的参数必须为数组或字符串");
        }

        return $this;
    }

    //limit语句
    public function limit( $limit )
    {
        $this->alias['limit'] = '';
        if( is_numeric( $limit ) ){
            $this->alias['limit'] = '0,' . $limit;
        }else if( is_string( $limit ) ){
            $this->alias['limit'] = $limit;
        }else{
            throw new \exception("limit语句的参数必须为数字或字符串");
        }

        return $this;
    }

    //order语句
    public function order( $order )
    {
        if( !is_string( $order ) ){
            throw new \exception("order语句的参数必须为字符串");
        }

        $this->alias['order'] = $order;
        return $this;
    }

    //group语句
    public function group( $group )
    {
        if( !is_string( $group ) ){
            throw new \exception("group语句的参数必须为字符串");
        }

        $this->alias['group'] = $group;
        return $this;
    }

    //解析查询sql语句
    public function ParseSelectSql()
    {
        $this->sql = 'select *';
        if( !empty( $this->alias['field'] ) ){
            $this->sql = str_replace( '*', $this->alias['field'], $this->sql );
        }

        if( empty( $this->alias['table'] ) ){
            throw new \exception("请用table子句设置查询表");
        }else{

            $this->sql .= ' from ' . $this->alias['table'];
        }

        if( !empty( $this->alias['where'] ) ){
            $this->sql .= ' where ' . $this->alias['where'];
        }

        if( !empty( $this->alias['group'] ) ){
            $this->sql .= ' group by ' . $this->alias['group'];
        }

        if( !empty( $this->alias['order'] ) ){
            $this->sql .= ' order by ' . $this->alias['order'];
        }

        if( !empty( $this->alias['limit'] ) ){
            $this->sql .= ' limit ' . $this->alias['limit'];
        }

    }

    //解析添加sql语句
    public function ParseAddSql()
    {
        $this->sql = 'insert into ';
        if( empty( $this->alias['table'] ) ){
            throw new \exception("请用table子句设置添加表");
        }else{

            $this->sql .= $this->alias['table'] . ' set ';
        }

        return $this->sql;
    }

    //解析更新sql语句
    public function ParseUpdateSql()
    {
        $this->sql = 'update ';
        if( empty( $this->alias['table'] ) ){
            throw new \exception("请用table子句设置修改表");
        }else{

            $this->sql .= $this->alias['table'] . ' set ';
        }

        if( empty( $this->alias['where'] ) ){
            throw new \exception("更新语句必须有where子句指定条件");
        }

        return $this->sql;
    }

    //解析删除sql语句
    public function ParseDeleteSql()
    {
        $this->sql = 'delete from ';
        if( empty( $this->alias['table'] ) ){
            throw new \exception("请用table子句设置删除表");
        }else{

            $this->sql .= $this->alias['table'];
        }

        if( empty( $this->alias['where'] ) ){
            throw new \exception("删除语句必须有where子句指定条件");
        }

        $this->sql .= ' where ' . $this->alias['where'];

        return $this->sql;
    }


    //查询语句
    public function select()
    {
        $this->ParseSelectSql();
        $row = $this->conn->query( $this->sql )->fetchAll( \PDO::FETCH_ASSOC );
        $result = [];

        foreach( $row as $key=>$vo ){

            $arrObj = clone $this;  //clone当前对象防止对this对象造成污染
            $arrObj->data = $vo;
            $result[$key] = $arrObj;
            unset( $arrObj );
        }

        var_dump($result);exit;

        return $result;
    }

    //查询一条
    public function find()
    {
        $this->ParseSelectSql();
        $row = $this->conn->query( $this->sql )->fetch( \PDO::FETCH_ASSOC );

        $arrObj = clone $this;  //clone当前对象防止对this对象造成污染
        $arrObj->data = $row;
        $result = $arrObj;
        unset( $arrObj );

        return $result;
    }

    //添加数据
    public function add( $data )
    {
        if( !is_array( $data ) ){
            throw new \exception("添加数据add方法参数必须为数组");
        }

        $this->ParseAddSql();
        foreach( $data as $key=>$vo ){
            $this->sql .= " `{$key}` = '" . $vo . "',";
        }

        $this->conn->exec( rtrim( $this->sql, ',' ) );
        return $this->conn->lastInsertId();
    }

    //更新语句
    public function update( $data )
    {
        if( !is_array( $data ) ){
            throw new \exception("更新数据update方法参数必须为数组");
        }

        $this->ParseUpdateSql();
        foreach( $data as $key=>$vo ){
            $this->sql .= " `{$key}` = '" . $vo . "',";
        }

        $this->sql = rtrim( $this->sql, ',' ) . ' where ' . $this->alias['where'];
        return $this->conn->exec( $this->sql );

    }

    //删除语句
    public function delete()
    {
        $this->ParseDeleteSql();
        return $this->conn->exec( $this->sql );
    }

    //获取查询数据
    public function getData()
    {
        return $this->data;
    }

    //获取最后一次执行的sql语句
    public function getLastSql()
    {
        return $this->sql;
    }

    public function __get($name)
    {
        return $this->getData()[$name];
    }

    public function offsetExists($offset)
    {
        if( !isset( $this->getData()[$offset] ) ){
            return NULL;
        }
    }

    public function offsetGet($offset)
    {
        return $this->getData()[$offset];
    }

    public function offsetSet($offset, $value)
    {
        return $this->data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset( $this->data[$offset] );
    }
}