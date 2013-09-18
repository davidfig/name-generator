<?php
/* https://github.com/davidfig/name-generator */

namespace YYFNG;

require "config.php";

class Database {
    protected static $sql;
    protected static $lastResult;
    protected static $connected = FALSE;
	protected static $init = FALSE;

    private static function Init() {
		if (static::$init)
			return;

	    try {
			static::$sql = new \PDO( DB_LOCALHOST, DB_USER, DB_PASSWORD, array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8' ));
		    static::$sql->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
			static::$connected = TRUE;
	    }
	    catch (\PDOException $e) {
		print_r($e);
			static::$connected = FALSE;
	    }

		static::$init = TRUE;
    }

    public static function IsConnected() {
		static::Init();
        return static::$connected;
    }
	public static function LastInsertId() {
        if (!static::IsConnected())
            return;

		return static::$sql->lastInsertId();
	}
	
    public static function Query( $query, $parameters=array() ) {
        if (!static::IsConnected())
            return;

	    static::$lastResult=static::$sql->prepare( $query );
	    static::$lastResult->execute( $parameters );
        return static::$lastResult;
    }

    public static function FetchArray( $query, $parameters=array() ) {
        if (!static::IsConnected())
            return;

	    static::$lastResult=static::$sql->prepare( $query );
	    static::$lastResult->execute( $parameters );
        return static::$lastResult->fetch();
    }

    public static function Fetch( $query, $parameters=array() ) {
        if (!static::IsConnected())
            return;

	    static::$lastResult=static::$sql->prepare( $query );
	    static::$lastResult->execute( $parameters );
	    return static::$lastResult->fetch( \PDO::FETCH_ASSOC );
    }

    public static function FetchNext() {
        if (!static::IsConnected())
            return;

	    return static::$lastResult->fetch( \PDO::FETCH_ASSOC );
    }

	public static function FetchNextResult( $result ) {
		if (!static::IsConnected())
			return;

		return $result->fetch( \PDO::FETCH_ASSOC );
	}

	public static function FetchNextResultArray( $result ) {
		if (!static::IsConnected())
			return;

		return $result->fetch( \PDO::FETCH_ASSOC );
	}

    public static function FetchAll( $query, $parameters=array() ) {
        if (!static::IsConnected())
            return;

	    $result=static::$sql->prepare($query);
	    $result->execute($parameters);
	    return $result->fetchAll( \PDO::FETCH_ASSOC );
    }
}