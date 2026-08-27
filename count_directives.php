<?php
$c = file_get_contents('resources/views/conversations/index.blade.php');
$if = preg_match_all('/@if\s*\(/', $c);
$elseif = preg_match_all('/@elseif\s*\(/', $c);
$else = preg_match_all('/@else\b(?![a-zA-Z])/', $c);
$endif = preg_match_all('/@endif\b/', $c);
$foreach = preg_match_all('/@foreach\s*\(/', $c);
$endforeach = preg_match_all('/@endforeach\b/', $c);
echo "if=$if elseif=$elseif else=$else endif=$endif  => need endif=" . ($if+$elseif+$else) . "\n";
echo "foreach=$foreach endforeach=$endforeach\n";
