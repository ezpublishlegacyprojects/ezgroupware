<?php
$dir = glob( '*' );
foreach( $dir as $file )
{
    rename( $file, strtoupper( $file ) );
}
