/*
    YARA Rules for FLU RACE Challenge
    These rules detect common web shell and malicious code patterns
*/

rule PHP_Webshell_Generic
{
    meta:
        description = "Generic PHP Webshell Detection"
        author = "FLU RACE Challenge"
        date = "2025-11-27"
        reference = "CTF Challenge"

    strings:
        $php_tag1 = "<?php"
        $php_tag2 = "<?="
        $php_tag3 = "<script language=\"php\">"
        
        // Common dangerous functions
        $exec1 = "exec("
        $exec2 = "system("
        $exec3 = "shell_exec("
        $exec4 = "passthru("
        $eval1 = "eval("
        $file1 = "file_get_contents("
        $file2 = "file_put_contents("
        
        // Common webshell patterns
        $shell1 = "$_GET"
        $shell2 = "$_POST"
        $shell3 = "$_REQUEST"
        $shell4 = "base64_decode("
        
        // Command execution patterns
        $cmd1 = /\$_[A-Z]+\[\s*['"'][^'"]*['"']\s*\]/
        $cmd2 = "cmd"
        $cmd3 = "command"

    condition:
        any of ($php_tag*) and 
        (
            any of ($exec*) or 
            any of ($eval*) or 
            (any of ($shell*) and any of ($cmd*))
        )
}

rule PHP_Backdoor_Functions
{
    meta:
        description = "PHP Backdoor Functions"
        author = "FLU RACE Challenge"

    strings:
        $func1 = "assert("
        $func2 = "preg_replace(" nocase
        $func3 = "create_function(" nocase
        $func4 = "call_user_func(" nocase
        $func5 = "call_user_func_array(" nocase
        $func6 = "ReflectionFunction" nocase

    condition:
        any of them
}

rule Suspicious_Base64
{
    meta:
        description = "Suspicious Base64 encoded content"
        author = "FLU RACE Challenge"

    strings:
        $b64_1 = "base64_decode("
        $b64_2 = "base64_encode("
        
        // Common base64 encoded PHP functions
        $enc_exec = "ZXhlYw==" // exec
        $enc_system = "c3lzdGVt" // system  
        $enc_eval = "ZXZhbA==" // eval
        $enc_shell = "c2hlbGxfZXhlYw==" // shell_exec

    condition:
        any of ($b64_*) and any of ($enc_*)
}

rule Generic_Webshell_Indicators
{
    meta:
        description = "Generic webshell indicators"
        author = "FLU RACE Challenge"

    strings:
        // Common webshell variable names
        $var1 = "$cmd" nocase
        $var2 = "$command" nocase
        $var3 = "$shell" nocase
        $var4 = "$execute" nocase
        
        // HTTP parameter access
        $http1 = "$_GET[" nocase
        $http2 = "$_POST[" nocase
        $http3 = "$_REQUEST[" nocase
        $http4 = "$_COOKIE[" nocase

    condition:
        any of ($var*) and any of ($http*)
}

rule JavaScript_in_Image
{
    meta:
        description = "JavaScript code in image file"
        author = "FLU RACE Challenge"

    strings:
        $js1 = "<script"
        $js2 = "javascript:"
        $js3 = "eval("
        $js4 = "document."
        $js5 = "window."

    condition:
        any of them
}

rule Polyglot_File_Detection
{
    meta:
        description = "Detect polyglot files (files that are valid in multiple formats)"
        author = "FLU RACE Challenge"

    strings:
        // PHP in what appears to be an image
        $php_in_img = { FF D8 FF [0-100] 3C 3F 70 68 70 } // JPEG header followed by <?php
        
        // GIF with PHP
        $gif_php1 = { 47 49 46 38 37 61 [0-100] 3C 3F 70 68 70 } // GIF87a with <?php
        $gif_php2 = { 47 49 46 38 39 61 [0-100] 3C 3F 70 68 70 } // GIF89a with <?php
        
        // PNG with PHP  
        $png_php = { 89 50 4E 47 0D 0A 1A 0A [0-200] 3C 3F 70 68 70 } // PNG signature with <?php

    condition:
        any of them
}

rule Command_Injection_Patterns
{
    meta:
        description = "Command injection patterns"
        author = "FLU RACE Challenge"

    strings:
        $inject1 = "|"
        $inject2 = ";"
        $inject3 = "&"
        $inject4 = "`"
        $inject5 = "$("
        $inject6 = "&&"
        $inject7 = "||"
        
        // Common commands
        $cmd1 = "ls"
        $cmd2 = "cat"
        $cmd3 = "pwd"
        $cmd4 = "whoami"
        $cmd5 = "uname"
        $cmd6 = "id"

    condition:
        2 of ($inject*) and any of ($cmd*)
}