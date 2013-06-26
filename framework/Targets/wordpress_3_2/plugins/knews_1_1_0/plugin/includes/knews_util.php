<?php
// Returns true if $string is valid UTF-8 and false otherwise.
function is_utf8($str) {
    $c=0; $b=0;
    $bits=0;
    $len=strlen($str);
    for($i=0; $i<$len; $i++){
        $c=ord($str[$i]);
        if($c > 128){
            if(($c >= 254)) return false;
            elseif($c >= 252) $bits=6;
            elseif($c >= 248) $bits=5;
            elseif($c >= 240) $bits=4;
            elseif($c >= 224) $bits=3;
            elseif($c >= 192) $bits=2;
            else return false;
            if(($i+$bits) > $len) return false;
            while($bits > 1){
                $i++;
                $b=ord($str[$i]);
                if($b < 128 || $b > 191) return false;
                $bits--;
            }
        }
    }
    return true;
}

function cut_code($start, $end, $code, $delete) {

	$start_pos = strpos($code, $start);
	$end_pos = strpos($code, $end);

	if ($delete) {
		$start_pos = $start_pos + strlen($start);
	} else {
		$end_pos = $end_pos + strlen($end);
	}
	
	if ($start_pos === false || $end_pos === false) return '';
	return substr($code, $start_pos, $end_pos-$start_pos);	
}

function extract_code($start, $end, $code, $delete) {

	$start_pos = strpos($code, $start);
	$end_pos = strpos($code, $end);

	if (!$delete) {
		$start_pos = $start_pos + strlen($start);
	} else {
		$end_pos = $end_pos + strlen($end);
	}
	
	if ($start_pos === false || $end_pos === false) return $code;
	return substr($code, 0, $start_pos) . substr($code, $end_pos);	
}

function iterative_extract_code($start, $end, $code, $delete) {
	$pre = $code;
	$post = extract_code($start, $end, $code, $delete);
	while ($pre != $post) {
		$pre=$post;
		$post = extract_code($start, $end, $post, $delete);
	}
	return $post;
}

function prettyCut($text, $amountChars, $termination) {
	$text = strip_tags($text);
	$text = trim(str_replace("  ", " ", $text));

	if (strlen($text) > $amountChars) {
		$subChain = substr($text, 0, $amountChars);
		$indexLastSpace = strrpos($subChain," ");
		$text = substr($text,0, $indexLastSpace) . $termination;	
	}
	return $text;
}

function extractAndCut($inic, $end, $theHtml) {
	$pos = strpos($theHtml, $inic);
	$pos2 = strpos($theHtml, $end);
	
	$module='';
	if ($pos === false || $pos2 === false) {
	} else {
		$module = substr($theHtml, $pos+strlen($inic), $pos2 - ($pos + strlen($inic)));
	}
	
	return $module;
}
/*
function normalize($text) {
	return utf8tohtml($text, false);
}

// Thanks to silverbeat -eat- gmx -hot- at
function utf8tohtml($utf8, $encodeTags) {
    $result = '';
    for ($i = 0; $i < strlen($utf8); $i++) {
        $char = $utf8[$i];
        $ascii = ord($char);
        if ($ascii < 128) {
            // one-byte character
            $result .= ($encodeTags) ? htmlentities($char) : $char;
        } else if ($ascii < 192) {
            // non-utf8 character or not a start byte
        } else if ($ascii < 224) {
            // two-byte character
            $result .= htmlentities(substr($utf8, $i, 2), ENT_QUOTES, 'UTF-8');
            $i++;
        } else if ($ascii < 240) {
            // three-byte character
            $ascii1 = ord($utf8[$i+1]);
            $ascii2 = ord($utf8[$i+2]);
            $unicode = (15 & $ascii) * 4096 +
                       (63 & $ascii1) * 64 +
                       (63 & $ascii2);
            $result .= "&#$unicode;";
            $i += 2;
        } else if ($ascii < 248) {
            // four-byte character
            $ascii1 = ord($utf8[$i+1]);
            $ascii2 = ord($utf8[$i+2]);
            $ascii3 = ord($utf8[$i+3]);
            $unicode = (15 & $ascii) * 262144 +
                       (63 & $ascii1) * 4096 +
                       (63 & $ascii2) * 64 +
                       (63 & $ascii3);
            $result .= "&#$unicode;";
            $i += 3;
        }
    }
    return $result;
}*/

function rgb2hex($code) {
	for ($pos_char = 0; $pos_char < strlen($code); $pos_char++) {

		if (substr($code, $pos_char, 3)=='rgb') {
			
			$start_pos = strpos($code, '(', $pos_char);
			$end_pos = strpos($code, ')', $pos_char);
						
			if ($start_pos < $end_pos && $pos_char + 6 > $start_pos && $start_pos + 16 > $end_pos) {
				
				$rgb_detected = substr($code, $start_pos +1 , $end_pos-$start_pos-1);

				$rgb_detected = str_replace(' ', '', $rgb_detected);
				$rgb_detected = explode(',', $rgb_detected);
				
				if (is_array($rgb_detected) && sizeof($rgb_detected) == 3) {
					list($r, $g, $b) = $rgb_detected;

					$r = dechex($r<0?0:($r>255?255:$r));
					$g = dechex($g<0?0:($g>255?255:$g));
					$b = dechex($b<0?0:($b>255?255:$b));
					
					$colorhex = (strlen($r) < 2?'0':'').$r;
					$colorhex.= (strlen($g) < 2?'0':'').$g;
					$colorhex.= (strlen($b) < 2?'0':'').$b;

					$colorhex = '#' . strtoupper ($colorhex);
					
					$code = substr($code, 0, $pos_char) . $colorhex . substr($code, $end_pos + 1);
				}
			}
		}
	}
	return $code;
}

?>