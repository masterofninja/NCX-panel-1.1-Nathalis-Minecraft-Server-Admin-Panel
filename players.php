<?php
include 'config/config.php';
//******************************************************************************
//**   Minecraft Query
//******************************************************************************
	use xPaw\MinecraftQuery;
	use xPaw\MinecraftQueryException;


	define( 'MQ_SERVER_ADDR', SERVER_IP );
	define( 'MQ_SERVER_PORT', SERVER_PORT );
	define( 'MQ_TIMEOUT', 1 );

      
	// Display everything in browser, because some people can't look in logs for errors
	Error_Reporting( E_ALL | E_STRICT );
	Ini_Set( 'display_errors', true );

	require __DIR__ . '/class/MinecraftQuery.php';
	require __DIR__ . '/class/MinecraftQueryException.php';

	$Timer = MicroTime( true );

	$Query = new MinecraftQuery( );

	try
	{
		$Query->Connect( MQ_SERVER_ADDR, MQ_SERVER_PORT, MQ_TIMEOUT );
	}
	catch( MinecraftQueryException $e )
	{
		$Exception = $e;
	}

	$Timer = Number_Format( MicroTime( true ) - $Timer, 4, '.', '' );
//******************************************************************************


if (($Info = $Query->GetInfo( ))  !== false ) echo($Info["Players"]."/".$Info["MaxPlayers"]);
?>