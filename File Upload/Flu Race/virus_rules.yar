/*
    YARA Rules for FLU RACE Challenge
    These rules detect common web shell and malicious code patterns
    Tuned to allow race condition exploitation while still catching persistent shells
*/

rule PHP_Webshell_Detected
{
    meta:
        description = "PHP Webshell Detection for CTF"
        author = "FLU RACE Challenge"
        date = "2025-12-21"

    strings:
        $php = "<?php"
        
        // Dangerous function calls
        $system = "system("
        $exec = "exec("
        $shell_exec = "shell_exec("
        $passthru = "passthru("
        $eval = "eval("
        
    condition:
        $php and any of ($system, $exec, $shell_exec, $passthru, $eval)
}