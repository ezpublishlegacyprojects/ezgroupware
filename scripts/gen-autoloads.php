<?php

require_once 'ezc/Base/base.php';
function __autoload ( $className )
{
    ezcBase::autoload( $className );
}
$input = new ezcConsoleInput();

$input->registerOption( 
    new ezcConsoleOption( 
        'h',
        'help'
    )
);

$input->registerOption( 
    new ezcConsoleOption(
        'd',
        'dir',
        ezcConsoleInput::TYPE_STRING,
        null,
        true,
        'Process a directory.',
        'Processes a complete directory.'
  )
);


$input->registerOption( 
    new ezcConsoleOption(
        't',
        'target',
        ezcConsoleInput::TYPE_STRING,
        null,
        true,
        'autoload dir',
        'The subdirectory of the processed directory where to save the autoload file'
  )
);

$input->registerOption( 
    new ezcConsoleOption(
        'p',
        'prefix',
        ezcConsoleInput::TYPE_STRING,
        null,
        true,
        'classes prefix',
        'The prefix of the classes for which the autoload file should be created'
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

if ( $input->getOption( 'h' )->value===true )
{
    echo "help!";
}

$dirOpt = $input->getOption( 'd' )->value;
if ( $dirOpt===false )
{
    $dir=getcwd(  );
}
elseif( $dirOpt===true )
{
    die( 'Option d has been given without a value' );
}
elseif( is_dir( $dirOpt[0] ))
{
    $dir = realpath( $dirOpt[0]);
}
else
{
    die( 'No valid dir given with option d' );
}

$targetDirOpt = $input->getOption( 't' )->value;
if ( $targetDirOpt===false )
{
    $targetDir = $dir.'/autoloads';
}
elseif ( $targetDirOpt ===true )
{
    die( 'Option t has been given without a value' );
}
elseif ( is_dir( $dir.'/'.$targetDirOpt[0] ) )
{
    $targetDir = realpath( $dir.'/'.$targetDirOpt[0] );
}
elseif ( is_dir( $targetDirOpt[0] ) )
{
    $targetDir = realpath( $targetDirOpt[0] );
}
else
{
    die( 'No valid dir given with option t' );
}

$prefixOpt = $input->getOption( 'p' )->value;
if ( $prefixOpt===false )
{
    $prefix = preg_replace( '/^\/[a-z]*/', '', strrchr( $dir, '/' ));
}
elseif( $prefixOpt===true )
{
   die( 'Option p has been given without a value' ) ;
}
else
{
    $prefix = $prefixOpt[0];
}

$autoloadArray = getClassFileArray( getPhpFiles( $dir, strlen( $dir ) ) );
$autoloadArray = filterByPrefix( $autoloadArray, $prefix );

$filetext = "<?php \nreturn ";
$filetext .= var_export( $autoloadArray, true );
$filetext .= ";\n?>";

//The autoload framework searches also for two word prefixes. So we need to convert the prefix DbSchema to 
//the filename db_schema_autoload.php
preg_match_all( '/[A-Z][a-z]+/', $prefix, $matches );
$filename = strtolower( implode( '_', $matches[0] ) ).'_autoload.php';

file_put_contents( $targetDir.'/'.$filename , $filetext );





/**
 * filterByPrefix 
 *
 * One autoloadfile contains only classes with the same prefix as in the filename. So we have to 
 * filter the found files.
 * 
 * @param array $array "classname" => "path/to/classfile.php"
 * @param string $prefix 
 * @return array
 */
function filterByPrefix( $array, $prefix )
{
    $result = array(  );
    foreach( $array as $className => $file )
    {
        if ( preg_match( "/^([a-z]*)".$prefix."([A-Z][a-z0-9]*)?/", $className ) )
        {
            $result[$className]=$file;
        }
    }
    return $result;
}

/**
 * getClassFileArray 
 * Takes the filenames and returns the classnames and filenames.
 * 
 * @param array $files filenames including the path from the root-dir 
 * @return array "classname" => "path/to/filename.php"
 */
function getClassFileArray( $files )
{
    $result = array(  );
    foreach ( $files as $file )
    {
        if ( is_array( $class2file = getClass2File( $file )) )
        {
            $result = array_merge( $result, $class2file );
        }
    }
    return $result;
}

/**
 * getClass2File 
 * extract the class/interface-name from a given file.
 * 
 * @param string $file 
 * @return false, if the file doesn't contain a class/interface or an array classname => path/to/file
 */
function getClass2File( $file )
{
    global $dir;
    $tokens = token_get_all( file_get_contents( $dir.'/'.$file ) );
    $i = 0;
    do
    {
    } while( $tokens[++$i][0] !== T_CLASS 
          && $tokens[$i][0] !== T_INTERFACE
          && is_array( $tokens[$i] ) );

    if ( $tokens[$i][0]==T_CLASS || $tokens[$i][0]==T_INTERFACE )
    {
        return array( $tokens[$i+2][1] => $file );
    }
    else
    {
        return false;
    }
}

/**
 * getPhpFiles
 *
 * recursively crawles a directory to search for php files
 * 
 * @param string $dir dir to start without trailing /
 * @param int $cutLength The length of the path to the root-dir
 * @return array with path/to/filenames
 */
function getPhpFiles( $dir, $cutLength )
{
    $result = array(  );
    $files = glob( $dir.'/*' );
    foreach ( $files as $file )
    {
        if ( $file == '..' || $file == '.' )
        {
            continue;
        }
        if ( is_dir( $file )  )
        {
            $result = array_merge( $result, getPhpFiles( $file, $cutLength ) );
        }
        if ( substr( $file, -4 )=='.php' )
        {
            $result[]=substr( $file, $cutLength-strlen( $file )+1 );
        }
    }
    return $result;
}

?> 
