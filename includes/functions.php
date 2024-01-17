<?php

/**
 * Basic flatmap helper.
 * @param callable $transform_fn
 * @param array $array
 * @return array
 */
function array_flatmap(callable $transform_fn, array $array): array {
    $result = [];

    $flatten = function ($element) use (&$result, &$flatten, $transform_fn) {
        if (!is_array($element)) {
            # Element cannot be unwrapped
            $result[] =  $element;
        } else {
            # Unwrap element further
            array_walk($element, $flatten);
        }
    };

    # The elements are first transformed and then the resulting array is flattened
    $flatten(array_map($transform_fn, $array));
    return $result;
}

/**
 * Splits a string into substrings of specified lengths.
 * To enable the caller to destructure & validate the returned array safely,
 * its length is always the same as that of the lengths array:
 *  - If the string is shorter than the sum of lengths, it is split into as many substrings of
 *    the specified lengths as possible, and then the result is padded out with empty strings if necessary.
 *  - If the string is longer than the sum of lengths, the last substring in the results array includes
 *    the extra characters.
 * @param string $str The string to split
 * @param array $lengths Array containing the lengths of the desired substrings
 * @return array
 */
function str_split_custom(string $str, array $lengths): array {
    # If no lengths specified, do not split the string
    if (empty($lengths)) {
        return [$str];
    }

    $sections = [];
    foreach ($lengths as $length) {
        # Substring of desired length is appended to the result
        $sections[] = substr($str, 0, $length);
        # Same substring is removed from the input string
        $str = substr($str, $length);
    }

    # Concatenate the last section with the remaining string
    $sections[count($sections) - 1] .= $str;

    return $sections;
}


function mmmr($array, $output = 'mean'){ 
    #Provides basic statistical functions - default is mean; other $output parammeters are; 'median', 'mode' and 'range'.
	#Ian Hollender 2016 - adapted from the following, as it was an inacurate solution
	#http://phpsnips.com/45/Mean,-Median,-Mode,-Range-Of-An-Array#tab=snippet
	#Good example of PHP overloading variables with different data types - see the Mode code
	if(!is_array($array)){ 
        echo '<p>Invalid parammeter to mmmr() function: ' . $array . ' is not an array</p>';
		return FALSE; #input parammeter is not an array
    }else{ 
        switch($output){ #determine staistical output required
            case 'mean': #calculate mean or average
                $count = count($array); 
                $sum = array_sum($array); 
                $total = $sum / $count; 
            break; 
            case 'median': #middle value in an ordered list; caters for odd and even lists
                $count = count($array); 
				sort($array); #sort the list of numbers
				if ($count % 2 == 0) { #even list of numbers
					$med1 = $array[$count/2];
					$med2 = $array[($count/2)-1];
					$total = ($med1 + $med2)/2;
				}
				else { #odd list of numbers
					$total = $array[($count-1)/2]; 	
				}				
            break; 
            case 'mode': #most frequent value in a list; N.B. will only find a unique mode or no mode; 
                $v = array_count_values($array); #create associate array; keys are numbers in array, values are counts
                arsort($v); #sort the list of numbers in ascending order				
				
				if (count(array_unique($v)) == 1) { #all frequency counts are the same, as array_unique returns array with all duplicates removed!
					return 'No mode';
				}				
				$i = 0; #used to keep track of count of associative keys processes
                $modes = '';
				foreach($v as $k => $v){ #determine if a unique most frequent number, or return NULL by only looking at first two keys and frequency numbers in the sorted array					
					if ($i == 0) { #first number and frequency in array
						$max1 = $v;	#highest frequency of first number in array
						$modes = $k . ' ';
						$total = $k; #first key is the most frequent number;
					}
					if ($i > 0) { #second number and frequency in array
						$max2 = $v;	#highest frequency of second number in array					
						if ($max1 == $max2) { #two or more numbers with same max frequency; return NULL
							$modes = $modes . $k . ' ';
						}
						else {
							break;  
						}
					}
					$i++; #next item in $v array to be counted
				}
				$total = $modes;				
            break; 
            case 'range': #highest value - lowest value
                sort($array); #find the smallest number
                $sml = $array[0]; 
                rsort($array); #find the largest number
                $lrg = $array[0]; 
                $total = $lrg - $sml; #calculate the range
            break; 
			default :
				echo '<p>Invalid parammeter to mmmr() function: ' . $output . '</p>';
				$total= 0;
				return FALSE;
        } 
        return $total; 
    } 
}

?>



