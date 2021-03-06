<?php

/*
 * Example PHP implementation used for the index.html example
 */

// DataTables PHP library
include( "../../libs/DataTables.php" );

// Alias Editor classes so they are easy to use
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate,
	DataTables\Editor\ValidateOptions;

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'tb_discoverers' )
	->fields(
		Field::inst( 'id' )
			->validator( Validate::notEmpty( ValidateOptions::inst()
				->message( 'A id  is required' )	
			) ),
		Field::inst( 'discoverer' )
			->validator( Validate::notEmpty( ValidateOptions::inst()
				->message( 'A discoverer name is required' )	
			) ),
	)
	->process( $_POST )
	->json();
