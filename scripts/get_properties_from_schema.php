<?php

/**
 * Script to automatically write models from DatabaseSchemas. 
 * Partly Copied from PersistentObjectDatabaseSchemaTiein
 */


require_once 'ezc/Base/base.php';
function __autoload ( $className )
{
    ezcBase::autoload( $className );
}
$input = new ezcConsoleInput();

$input->registerOption(
        new ezcConsoleOption(
            "s",        // short
            "source",   // long
            ezcConsoleInput::TYPE_STRING,
            null,       // default
            false,      // multiple
            "DatabaseSchema source to use.",
            "The DatabaseSchema to use for the generation of the PersistentObject definition. Or the DSN to the database to grab the schema from.",
            array(),    // dependencies
            array(),    // exclusions
            true,       // arguments
            true        // mandatory
            )
        );

$schemaFormats = implode( ", ", ezcDbSchemaHandlerManager::getSupportedFormats() );
$input->registerOption(
        new ezcConsoleOption(
            "f",        // short
            "format",   // long
            ezcConsoleInput::TYPE_STRING,
            null,       // default
            false,      // multiple
            "DatabaseSchema format of the input source.",
            "The format, the input DatabaseSchema is in. Valid formats are {$schemaFormats}.",
            array(),    // dependencies
            array(),    // exclusions
            true,       // arguments
            true        // mandatory
            )
        );

try
{
    $input->process();
}
catch ( ezcConsoleOptionException $e )
{
    die( $e->getMessage() );
}

// Get the schemaObject
try
{
    $readerClass = ezcDbSchemaHandlerManager::getReaderByFormat( $input->getOption( "format" )->value );
    $reader = new $readerClass();

    switch ( true )
    {
        case ( $reader instanceof ezcDbSchemaDbReader ):
            $db = ezcDbFactory::create( $input->getOption( "source" )->value );
            $schema = ezcDbSchema::createFromDb( $db );
            break;
        case ( $reader instanceof ezcDbSchemaFileReader ):
            $schema = ezcDbSchema::createFromFile( $input->getOption( "format" )->value, $input->getOption( "source" )->value );
            break;
        default:
            echo( "Reader class not supported: '{$readerClass}'.\n" );
            break;
    }
}
catch ( Exception $e )
{
    die( "Error reading schema: {$e->getMessage()}\n" );
}

// Get the actual schemaStruct
$schemaStruct = $schema->getSchema();

foreach( $schemaStruct as $tableName => $table )
{
    echo $tableName.': ';
    foreach( $table->fields as $field => $dummy )
    {
        echo "'".$field."', ";
    }
    echo"\n";
}

?> 
