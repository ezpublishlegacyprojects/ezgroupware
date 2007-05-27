<?php

require_once 'PHP/CodeSniffer/Sniff.php';

class egw_functionHasDocBlockSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_FUNCTION);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $runner = $stackPtr;
        $DocBlock = null;
        $error = '';

        do
        {
            $runner--;
            $token=$tokens[$runner];
            $code=$token['code'];
            if ( $code===T_DOC_COMMENT )
            {
                $DocBlock=$token;
            }
        }
        while (!( $code === T_CLOSE_CURLY_BRACKET
               || $code === T_SEMICOLON
               || $code === T_CLASS
               || $runner < 2
               || $stackPtr - $runner > 6
            ));

        // Get the Functionname
        if ( $tokens[$stackPtr+2]['code'] !== T_STRING )
        {
            // Maybe it's an & to return by reference?
            if ( $tokens[$stackPtr+2]['code']=== T_BITWISE_AND && $tokens[$stackPtr+3]['code'] === T_STRING)
            {
                $functionName = $tokens[$stackPtr+3]['code'];
            }
            else
            {
                $error .= 'Functionname missplaced!';
            }
        }
        else
        {
            $functionName = $tokens[$stackPtr+2]['content'];
        }

        // Is there a nice DocBlock?
        if ( $DocBlock===null )
        {
            $error="Function $functionName has no DocBlockComment!";
        }

        //Any errors to report?
        if ( $error !=='' )
        {
            $phpcsFile->addError( $error, $stackPtr );
        }

    }//end process()


}//end class

?>

