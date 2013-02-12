<?php
/**
 * Various Functions
 *
 * @author      CloneUI <opensource@cloneui.com>
 * @copyright   2012 CloneUI
 * @link        http://cloneui.com
 *
 * @since       Wednesday, April 20, 2011 / 10:30 PM GMT+1
 * @edited      $Date: 2012-02-14 12:20:54 +0100 (Tue, 14 Feb 2012) $
 * @version     $Id: functions.php 6605 2012-02-14 11:20:54Z mknox $
 *
 * @package     Instagram Clone
 */

/**
 * fetch file extension
 *
 * @param   string
 * @return  string
 */
function fetchFileExt($file)
{
    return substr($file, strrpos($file, '.', -1) + 1);
}

/**
 * recursive glob
 *
 * @author  arvin@sudocode.net
 * @param   string  $path       path of folder to search
 * @param   string  $pattern    glob pattern
 * @param   string  $flags      glob flags
 * @param   string  $depth      0 for current folder only,
 *                              1 to descend 1 folder down and so on.
 *                              -1 for no limit.
 * @link    http://www.php.net/manual/en/function.glob.php#101017
 * @return  array
 */
function bfglob($path, $pattern = '*', $flags = 0, $depth = 0)
{
    $matches = array();
    $folders = array(rtrim($path, DIRECTORY_SEPARATOR));

    while($folder = array_shift($folders)) {
        $matches = array_merge($matches, glob($folder.DIRECTORY_SEPARATOR.$pattern, $flags));
        if($depth != 0) {
            $moreFolders    = glob($folder.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR);
            $depth          = ($depth < -1) ? -1: $depth + count($moreFolders) - 2;
            $folders        = array_merge($folders, $moreFolders);
        }
    }

    return $matches;
}

/**
 * fetch filename
 *
 * @param   string
 * @return  string
 */
function fetchFilename($file)
{
    return substr($file, 0, strrpos($file, '.', -1));
}

/**
 * determine the server URL
 *
 * @return  string
 */
function fetchServerURL()
{
    $url = fetchCurrentURL();

    if(preg_match('/phpunit/', $url)) {
        return 'phpunit';
    }

    $url = parse_url($url);

    if(!strlen(@$url['path'])) {
        return;
    }

    $pathinfo   = pathinfo($url['path']);
    $serverURL  = 'http';

    if (@$_SERVER['HTTPS'] == 'on') {
	    $serverURL .= 's';
	}

	$serverURL    .= "://";
 	$serverURL    .= @$_SERVER['HTTP_HOST'];
	$serverURL    .= $pathinfo['dirname'];
 
    return $serverURL;
}

/**
 * determine the current URL
 *
 * @return  string
 */
function fetchCurrentURL()
{
    if(strlen(@$_SERVER['SHELL'])) {
        return $_SERVER['PHP_SELF'];
    }

    $pageURL = 'http';

    if (@$_SERVER['HTTPS'] == 'on') {
	    $pageURL .= 's';
	}

	$pageURL    .= "://";
 	$pageURL    .= (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '';
	$pageURL    .= $_SERVER['PHP_SELF'];
	$queryString = (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : '';

    if(strlen($queryString)) {
	    $pageURL .= '?'.$queryString;
	}

    return $pageURL;
}

/**
 * convert a string to a valid MySQL datetime value
 *
 * @param   string  $str
 * @return  string
 */
function stringToMySQLDateTime($str)
{
    return date('Y-m-d H:i:s', strtotime($str));
}

/**
 * convert a string to a valid MySQL date value
 *
 * @param   string  $str
 * @return  string
 */
function stringToMySQLDate($str)
{
    return date('Y-m-d', strtotime($str));
}

/**
 * convert a string to the month year
 *
 * @param   string  $str
 * @return  string
 */
function stringToMonthYear($str)
{
    return date('m/Y', strtotime($str));
}

/**
 * remove common word from text
 *
 * @link    http://soulpass.com/2008/07/11/php-tag-cloud-remove-common-words
 * @param   mixed   $text           string or array
 * @param   array   $filter         array of words to filter out
 * @param   int     $minWordLength  minimum length of words to keep
 * @return  array
 */
function filterCommonWords($text, $filter = array(), $minWordLength = 3)
{
    $multiWordPharse = array();
    $defaultFilter = array('able', 'about', 'after', 'again',
                            'all', 'also', 'and', 'any', 'are',
                            'bad', 'been', 'before', 'being',
                            'between', 'but', 'came', 'can',
                            'cause', 'change', 'come', 'could',
                            'did', 'differ', 'different', 'does',
                            'don', 'down', 'each', 'end',
                            'even', 'every', 'far', 'few',
                            'for', 'form', 'found', 'four',
                            'from', 'get', 'good', 'great',
                            'had', 'has', 'have', 'her',
                            'here', 'him', 'his', 'how',
                            'into', 'its', 'just', 'keep',
                            'let', 'many', 'may', 'might',
                            'more', 'most', 'much', 'must',
                            'near', 'need', 'never', 'new',
                            'next', 'not', 'now', 'off',
                            'one', 'only', 'other', 'our',
                            'out', 'over', 'part', 'put',
                            'said', 'same', 'say', 'seem',
                            'set', 'should', 'side', 'some',
                            'still', 'such', 'take', 'than',
                            'that', 'the', 'their', 'them',
                            'then', 'there', 'these', 'they',
                            'thing', 'this', 'three', 'through',
                            'too', 'two', 'upon', 'use',
                            'very', 'was', 'way', 'went',
                            'were', 'what', 'when', 'where',
                            'which', 'while', 'who', 'will',
                            'with', 'would', 'you', 'your',
                            'lol', 'omg', 'wtf', 'lmao',
                            'tweet', 'pics', 'pix', 'smh');

    $filter = (empty($filter)) ? $defaultFilter : $filter;

    if(is_array($text)) {
        foreach($text AS $key => $value) {
            if(str_word_count($value) > 1) {
                $multiWordPharse[] = $value;
                unset($text[$key]);
            }
        }
        $text = implode(' ', $text);
    }

    preg_match_all('/([\+a-zA-Z0-9\._\'-]{'.$minWordLength.',})/', $text, $matches);
    $matches[0] = array_map('strtolower', $matches[0]);
    $output     = array_diff($matches[0], $filter);

    if(!empty($multiWordPharse)) {
        $output = array_merge($output, $multiWordPharse);
    }

    return array_unique($output);
}

/**
 * detect URLs in text
 *
 * @param   string  $text
 * @return  array
 */
function detectURLs($text)
{
    $pattern    = '(((http)(s?)\:\/\/))';
    $pattern   .= '[A-Za-z0-9][A-Za-z0-9.-]+(:\d+)?(\/[^ ]*)?';

    if(preg_match_all('/'.$pattern.'/', $text, $matches)) {
        return $matches[0];
    }
}

/**
 * detect partial URLs in text
 *
 * @param   string  $text
 * @return  array
 */
function detectPartialHttpUrls($text)
{
    $pattern = '(http|https)(:\/\/)?+[^\s)]*';

    if(preg_match_all('/'.$pattern.'/', $text, $matches)) {
        return $matches[0];
    }
}

/**
 * detect retweets in text
 *
 * @param   string  $text
 * @return  array
 */
function detectRTs($text)
{
    $pattern = '/RT @(\w+)+(:)?/';

    if(preg_match_all($pattern, $text, $matches)) {
        return $matches[0];
    }
}

/**
 * determine if a Tweet is a ReTweet
 *
 * @param   string  $text
 * @return  array
 */
function isRT($text)
{
    $pattern = '/RT @(\w+)+(:)?/';

    if(preg_match($pattern, $text, $matches)) {
        return true;
    }
}

/**
 * detect mentions in text
 *
 * @param   string      $text
 * @param   boolean     $argressive
 * @return  array
 */
function detectMentions($text, $aggressive = true)
{
    if($aggressive) {
        $pattern = '/@(\w+)+(_)?+(\'s)?/';
    } else {
        $pattern = '/(^|\s)@(\w+)+(_)?+(\'s)?/';
    }

    if(preg_match_all($pattern, $text, $matches)) {
        return $matches[0];
    }

    $pattern = '/@\/(\w+)+(_)?+(\'s)?/';

    if(preg_match_all($pattern, $text, $matches)) {
        return $matches[0];
    }
}

/**
 * detect mentions in text
 *
 * @param   string  $text
 * @return  array
 */
function detectMentionUp($text)
{

    $pattern = '/\^(\w+)+(_)?+(\'s)?/';

    if(preg_match_all($pattern, $text, $matches)) {
        return $matches[0];
    }
}

/**
 * detect hashtags in tweets
 *
 * @param   string  $text
 * @return  array
 */
function detectHashTags($text)
{
    $pattern = '/(^|\s)#(\w+)/';

    if(preg_match_all($pattern, $text, $matches)) {
        return $matches[0];
    }
}

/**
 * remove URLs from text
 *
 * @param   string  $text
 * @return  string
 */
function removeUrls($text)
{
    $urls = detectURLs($text);
    if(!empty($urls)) {
        foreach($urls AS $key => $value) {
            $text = str_ireplace($value, '', $text);
        }
    }

    return trim($text);
}

/**
 * remove all URLs, including partial
 * ones from text
 *
 * @param   string  $text
 * @return  string
 */
function removePartialHttpUrls($text)
{
    $urls = detectPartialHttpUrls($text);
    if(!empty($urls)) {
        foreach($urls AS $key => $value) {
            $text = str_ireplace($value, '', $text);
        }
    }

    return trim($text);
}

/**
 * remove Twitter hashtags from text
 *
 * @param   string  $text
 * @return  string
 */
function removeHashTags($text)
{
    $tags = detectHashTags($text);
    if(!empty($tags)) {
        foreach($tags AS $key => $value) {
            $text = str_ireplace($value, '', $text);
        }
    }

    return trim($text);
}

/**
 * remove @mentions from text
 *
 * @param   string  $text
 * @return  string
 */
function removeMentions($text)
{
    $tags = detectMentions($text);
    if(!empty($tags)) {
        foreach($tags AS $key => $value) {
            $text = str_ireplace($value, '', $text);
        }
    }

    return trim($text);
}

/**
 * remove @mentions from text
 *
 * @param   string  $text
 * @return  string
 */
function removeMentionUp($text)
{
    $tags = detectMentionUp($text);
    if(!empty($tags)) {
        foreach($tags AS $key => $value) {
            $text = str_ireplace($value, '', $text);
        }
    }

    return trim($text);
}

/**
 * remove RTs from text
 *
 * @param   string  $text
 * @return  string
 */
function removeRTs($text)
{
    $tags = detectRTs($text);
    if(!empty($tags)) {
        foreach($tags AS $key => $value) {
            $text = str_ireplace($value, '', $text);
        }
    }

    return trim($text);
}

/**
 * detect numbers succeeded by a dash
 *
 * @param   string  $text
 * @return  array
 */
function detectNumbersWithDashes($text)
{
    if(preg_match_all('/(?P<digit>\d+)+(-)/', $text, $matches)) {
        return $matches[0];
    }
}

/**
 * remove numbers succeeded by dashes from text
 *
 * @param   string  $text
 * @return  string
 */
function removeNumbersWithDashes($text)
{
    $tags = detectNumbersWithDashes($text);
    if(!empty($tags)) {
        foreach($tags AS $key => $value) {
            $text = str_ireplace($value, '', $text);
        }
    }

    return trim($text);
}

/**
 * get_redirect_url()
 * Gets the address that the provided URL redirects to,
 * or FALSE if there's no redirect.
 *
 * @link    http://w-shadow.com/blog/2008/07/05/how-to-get-redirect-url-in-php/
 * @param   string  $url
 * @return  string
 */
function get_redirect_url($url)
{
	$redirect_url = null;

    if(!preg_match('/http/', $url) AND !preg_match('/https/', $url)) {
        $url = 'http://'.$url;
    }

	$url_parts = @parse_url($url);
	if (!$url_parts) return false;
	if (!isset($url_parts['host'])) {
        // can't process relative URLs
        return false;
	}
	if (!isset($url_parts['path'])) $url_parts['path'] = '/';

	$sock = fsockopen($url_parts['host'], (isset($url_parts['port']) ? (int)$url_parts['port'] : 80), $errno, $errstr, 30);
	if (!$sock) return false;

	$request = "HEAD " . $url_parts['path'] . (isset($url_parts['query']) ? '?'.$url_parts['query'] : '') . " HTTP/1.1\r\n";
	$request .= 'Host: ' . $url_parts['host'] . "\r\n";
	$request .= "Connection: Close\r\n\r\n";
	fwrite($sock, $request);
	$response = '';
	while(!feof($sock)) $response .= fread($sock, 8192);
	fclose($sock);

	if (preg_match('/^Location: (.+?)$/m', $response, $matches)) {
		if ( substr($matches[1], 0, 1) == "/" )
			return $url_parts['scheme'] . "://" . $url_parts['host'] . trim($matches[1]);
		else
			return trim($matches[1]);

	} else {
		return false;
	}
}

/**
 * get_all_redirects()
 * Follows and collects all redirects, in order, for the given URL.
 *
 * @param   string  $url
 * @return  array
 */
function get_all_redirects($url)
{
	$redirects = array();
	while ($newurl = get_redirect_url($url)) {
		if (in_array($newurl, $redirects)) {
			break;
		}
		$redirects[] = $newurl;
		$url = $newurl;
	}
	return $redirects;
}

/**
 * get_final_url()
 * Gets the address that the URL ultimately leads to.
 * Returns $url itself if it isn't a redirect.
 *
 * @param   string $url
 * @return  string
 */
function get_final_url($url)
{
	$redirects = get_all_redirects($url);
	if (count($redirects) > 0) {
		return array_pop($redirects);
	} else {
		return $url;
	}
}

/**
 * @author   "Sebastián Grignoli" <grignoli@framework2.com.ar>
 * @package  forceUTF8
 * @version  1.1
 * @link     http://www.framework2.com.ar/dzone/forceUTF8-es/
 * @example  http://www.framework2.com.ar/dzone/forceUTF8-es/
  */

function forceUTF8($text)
{
/**
 * Function forceUTF8
 *
 * This function leaves UTF8 characters alone, while converting almost all non-UTF8 to UTF8.
 *
 * It may fail to convert characters to unicode if they fall into one of these scenarios:
 *
 * 1) when any of these characters:   ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞß
 *    are followed by any of these:  ("group B")
 *                                    ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶•¸¹º»¼½¾¿
 * For example:   %ABREPRESENT%C9%BB. «REPRESENTÉ»
 * The "«" (%AB) character will be converted, but the "É" followed by "»" (%C9%BB)
 * is also a valid unicode character, and will be left unchanged.
 *
 * 2) when any of these: àáâãäåæçèéêëìíîï  are followed by TWO chars from group B,
 * 3) when any of these: ðñòó  are followed by THREE chars from group B.
 *
 * @name forceUTF8
 * @param string $text  Any string.
 * @return string  The same string, UTF8 encoded
 *
 */

  if(is_array($text))
    {
      foreach($text as $k => $v)
    {
      $text[$k] = forceUTF8($v);
    }
      return $text;
    }

    $max = strlen($text);
    $buf = "";
    for($i = 0; $i < $max; $i++){
        $c1 = $text{$i};
        if($c1>="\xc0"){ //Should be converted to UTF8, if it's not UTF8 already
          $c2 = $i+1 >= $max? "\x00" : $text{$i+1};
          $c3 = $i+2 >= $max? "\x00" : $text{$i+2};
          $c4 = $i+3 >= $max? "\x00" : $text{$i+3};
            if($c1 >= "\xc0" & $c1 <= "\xdf"){ //looks like 2 bytes UTF8
                if($c2 >= "\x80" && $c2 <= "\xbf"){ //yeah, almost sure it's UTF8 already
                    $buf .= $c1 . $c2;
                    $i++;
                } else { //not valid UTF8.  Convert it.
                    $cc1 = (chr(ord($c1) / 64) | "\xc0");
                    $cc2 = ($c1 & "\x3f") | "\x80";
                    $buf .= $cc1 . $cc2;
                }
            } elseif($c1 >= "\xe0" & $c1 <= "\xef"){ //looks like 3 bytes UTF8
                if($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf"){ //yeah, almost sure it's UTF8 already
                    $buf .= $c1 . $c2 . $c3;
                    $i = $i + 2;
                } else { //not valid UTF8.  Convert it.
                    $cc1 = (chr(ord($c1) / 64) | "\xc0");
                    $cc2 = ($c1 & "\x3f") | "\x80";
                    $buf .= $cc1 . $cc2;
                }
            } elseif($c1 >= "\xf0" & $c1 <= "\xf7"){ //looks like 4 bytes UTF8
                if($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf" && $c4 >= "\x80" && $c4 <= "\xbf"){ //yeah, almost sure it's UTF8 already
                    $buf .= $c1 . $c2 . $c3;
                    $i = $i + 2;
                } else { //not valid UTF8.  Convert it.
                    $cc1 = (chr(ord($c1) / 64) | "\xc0");
                    $cc2 = ($c1 & "\x3f") | "\x80";
                    $buf .= $cc1 . $cc2;
                }
            } else { //doesn't look like UTF8, but should be converted
                    $cc1 = (chr(ord($c1) / 64) | "\xc0");
                    $cc2 = (($c1 & "\x3f") | "\x80");
                    $buf .= $cc1 . $cc2;
            }
        } elseif(($c1 & "\xc0") == "\x80"){ // needs conversion
                $cc1 = (chr(ord($c1) / 64) | "\xc0");
                $cc2 = (($c1 & "\x3f") | "\x80");
                $buf .= $cc1 . $cc2;
        } else { // it doesn't need convesion
            $buf .= $c1;
        }
    }
    return $buf;
}

function forceLatin1($text) {
  if(is_array($text)) {
    foreach($text as $k => $v) {
      $text[$k] = forceLatin1($v);
    }
    return $text;
  }
  return utf8_decode(forceUTF8($text));
}

function fixUTF8($text){
  if(is_array($text)) {
    foreach($text as $k => $v) {
      $text[$k] = fixUTF8($v);
    }
    return $text;
  }

  $last = "";
  while($last <> $text){
    $last = $text;
    $text = forceUTF8(utf8_decode(forceUTF8($text)));
  }
  return $text;
}

/**
 * fetch the response of a URL via cURL
 *
 * @param   string  $url
 * @param   boolean $returnCurlInfo
 * @param   int     $timeout
 * @param   int     $maxRedirs
 * @return  string
 */
function fetchUrlWithCurl($url, $returnCurlInfo = false, $timeout = 60, $maxRedirs = 10)
{
    if(!strlen($url)) {
        return;
    }

    $referers   = array('www.google.com',
                        'yahoo.com',
                        'msn.com',
                        'ask.com',
                        'live.com'
                    );
    $referer    = array_rand($referers);
    $referer    = 'http://' . $referers[$referer];

    $browsers   = array('Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0',
                        'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.3) Gecko/2008092510 Ubuntu/8.04 (hardy) Firefox/3.0.3',
                        'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20060918 Firefox/2.0',
                        'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3',
                        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506)',
                        'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0',
                        'Googlebot/2.1 (+http://www.google.com/bot.html)'
                    );
    $browser    = array_rand($browsers);
    $browser    = $browsers[$browser];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERAGENT, $browser);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, $maxRedirs);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    if($returnCurlInfo) {
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    }

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

    $response = curl_exec($ch);

    if($returnCurlInfo) {
        $originalResponse   = $response;
        $response           = array();
        $response['info']   = curl_getinfo($ch);
        $response['html']   = $originalResponse;
    }

    if($error = curl_error($ch)) {
        $response['error']      = $error;
        $response['errorno']    = curl_errno($ch);
    }

    curl_close($ch);

    return $response;
}

/**
 * fetch the final URL of a URL via cURL
 *
 * @param   string  $url
 * @param   boolean $returnCurlInfo
 * @param   int     $timeout
 * @param   int     $maxRedirs
 * @return  string
 */
function curl_fetch_final_url($url, $returnCurlInfo = false, $timeout = 60, $maxRedirs = 10)
{
    if(!strlen($url)) {
        return;
    }

    if(!preg_match('/http/', $url) AND !preg_match('/https/', $url)) {
        $url = 'http://'.$url;
    }

    $referers   = array('www.google.com',
                        'yahoo.com',
                        'msn.com',
                        'ask.com',
                        'live.com'
                    );
    $referer    = array_rand($referers);
    $referer    = 'http://' . $referers[$referer];

    $browsers   = array('Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0',
                        'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.3) Gecko/2008092510 Ubuntu/8.04 (hardy) Firefox/3.0.3',
                        'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20060918 Firefox/2.0',
                        'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3',
                        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506)',
                        'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0',
                        'Googlebot/2.1 (+http://www.google.com/bot.html)'
                    );
    $browser    = array_rand($browsers);
    $browser    = $browsers[$browser];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERAGENT, $browser);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, $maxRedirs);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    if($returnCurlInfo) {
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    }

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

    $response = curl_exec($ch);
    $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

    if($returnCurlInfo) {
        $originalResponse   = $response;
        $response           = array();
        $response['info']   = curl_getinfo($ch);
        $response['html']   = $originalResponse;
    }

    if($error = curl_error($ch)) {
        $response['error']      = $error;
        $response['errorno']    = curl_errno($ch);
    }

    curl_close($ch);

    return $finalUrl;
}

/**
 * fetch the HTTP response code of a URL via cURL
 *
 * @param   string  $url
 * @param   int     $timeout
 * @param   int     $maxRedirs
 * @return  string
 */
function getHttpResponse($url, $timeout = 60, $maxRedirs = 10)
{
    if(!strlen($url)) {
        return;
    }

    $referers   = array('www.google.com',
                        'yahoo.com',
                        'msn.com',
                        'ask.com',
                        'live.com'
                    );
    $referer    = array_rand($referers);
    $referer    = 'http://' . $referers[$referer];

    $browsers   = array('Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0',
                        'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.3) Gecko/2008092510 Ubuntu/8.04 (hardy) Firefox/3.0.3',
                        'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20060918 Firefox/2.0',
                        'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3',
                        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506)'
                    );
    $browser    = array_rand($browsers);
    $browser    = $browsers[$browser];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERAGENT, $browser);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, $maxRedirs);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_HEADER, true);
    $response = curl_exec($ch);

    return curl_getinfo($ch, CURLINFO_HTTP_CODE);
}

/**
 * detect Google redirection page
 *
 * @param   string  $url
 * @return  boolean
 */
function isGoogleRedirect($url)
{
    if(preg_match('/http:\/\/www.google.com\/url?/', $url)) {
        return true;
    }
}

/**
 * extract the redirection URL in a Google redirection page
 *
 * @param   string  $url
 * @return  boolean
 */
function extractGoogleRedirect($url)
{
    if(preg_match('/&url=(.+)$/is', $url, $matches)) {
        if(!empty($matches)) {
            return urldecode(str_replace('&url=', '', $matches[0]));
        }
    }
}

/**
 * determine if the content type is an image
 *
 * @param   string  $contentType
 * @return  boolean
 */
function isImage($contentType)
{
    if(preg_match('/image/i', $contentType)) {
        return true;
    }
}

/**
 * determine if the content type is HTML
 *
 * @param   string  $contentType
 * @return  boolean
 */
function isHtml($contentType)
{
    if(preg_match('/html/i', $contentType)) {
        return true;
    }
}

/**
 * fetch the Google +1 count for a URL
 *
 * @link    http://lucido-media.de/blog/php-google-plus-one-count-api
 * @link    http://www.tomanthony.co.uk/blog/google_plus_one_button_seo_count_api/
 *
 * @param   string  $url
 * @param   string  $apiKey
 * @return  int
 */
function get_google_plus1_count($url, $apiKey = 'AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ')
{
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_USERAGENT       => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0',
        CURLOPT_REFERER         => 'http://google.com',
        CURLOPT_AUTOREFERER     => true,
        CURLOPT_FOLLOWLOCATION  => true,
        CURLOPT_MAXREDIRS       => 10,
        CURLOPT_HTTPHEADER      => array('Content-type: application/json'),
        CURLOPT_POST            => true,
        CURLOPT_POSTFIELDS      => '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.$url.'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]',
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_SSL_VERIFYPEER  => false,
        CURLOPT_URL             => 'https://clients6.google.com/rpc?key='.$apiKey
    ));

    $res = curl_exec($ch);
    curl_close($ch);

    if( $res ) {
        $json = json_decode($res, true);
        return $json[0]['result']['metadata']['globalCounts']['count'];
    }
}

/**
 * extract n-grams from a string
 *
 * @link    http://phpir.com/language-detection-with-n-grams
 *
 * @param   string  $url
 * @param   string  $apiKey
 * @return  int
 */
function getNgrams($word, $n = 3)
{
    $ngrams = array();
    $len    = strlen($word);

    for($i = 0; $i < $len; $i++) {
        if($i > ($n - 2)) {
            $ng = '';
            for($j = $n-1; $j >= 0; $j--) {
                $ng .= $word[$i-$j];
            }
            $ngrams[] = $ng;
        }
    }

    return $ngrams;
}

/**
 * Detect HTML language
 *
 * @param   string  $html
 * @return  string
 */
function detectHtmlLang($html)
{
    if(preg_match("/lang=[\"'][^\"']*[\"']/is", $html, $matches)) {
        $lang = trim($matches[0], 'lang=');
        $lang = trim($lang, 'LANG=');
        $lang = trim($lang, '"');
        $lang = trim($lang, "'");

        return trim($lang);
    }
}

/**
 * callback for Rolling Curl
 *
 * @link    http://code.google.com/p/rolling-curl/
 * @param   array   $response
 * @param   array   $info
 * @return  array
 */
function rolling_curl_request_callback($response, $info)
{
    $array              = array();
    $array['response']  = $response;
    $array['info']      = $info;

    return $array;
}

/**
 * remove bit.ly URLs
 *
 * @param   string  $text
 * @return  string
 */
function removeBitLyUrls($text)
{
    $pattern    = '(((http?)(s?)\:\/\/))?';
    $pattern   .= '(((bit.ly)\/))';
    $pattern   .= '[A-Za-z0-9][A-Za-z0-9.-]+(:\d+)?(\/[^ ]*)?';

    if(preg_match_all('/'.$pattern.'/i', $text, $matches)) {
        if(!empty($matches)) {
            foreach($matches AS $key => $value) {
                $text = str_ireplace($value, '', $text);
            }
        }
    }

    return $text;
}

function isShortenedUrl($text)
{
    $pattern    = '4sq.com|aaja.de|bit.ly|chortl.es|deck.ly|dthin.gs|engt.co|';
    $pattern   .= 'dw.am|ht.ly|ppit.co|sns.mx|j.mp|durl.me|dld.bz|fb.com|';
    $pattern   .= 'cnet.co|glft.co|spr.ly|imax.ly|stzlinx.de|img.ly|mjo.lt|';
    $pattern   .= 'ubi.li|slgr.co|tlk.tc|snd.sc|t.cn|arst.ch|vk.cc|';
    $pattern   .= 'fb.me|fur.ly|goo.gl|instagr.am|is.gd|lnk.co|moourl.com|';
    $pattern   .= 'ow.ly|spon.de|t.co|tmblr.co|p.ost.im|hgm.me|es.pn|shar.es|';
    $pattern   .= 'su.pr|t.hh.de|tinyurl.com|yhoo.it|youtu.be|jalo.ps|aol.it|';
    $pattern   .= 'dlvr.it|go.ign.com|dtoid.it|glo.bo|del.ly|lnk.co|see.sc|';
    $pattern   .= 'wp.me|amzn.to|usat.ly|prn.to|bbc.in|dspy.me|tcrn.ch|nyti.ms|';
    $pattern   .= 'sz.de|tiny.cc|silicon.de|g4.tv|tw.appstore.com|ots.de|';
    $pattern   .= 'heise.de|at.mtv.com|mjr.mn|lnkd.in|d3sanc.com|rfi.my|';
    $pattern   .= 'min.us|l.n24.de|snsanalytics.com|mainpost.de|tl.gd|';
    $pattern   .= 'j-tv.me|cnet.de|w.idg.de|ulinks.fr|zd.net|edmu.in|zite.to|';
    $pattern   .= 'fzt.me|spic.kr|wibi.us|say.ly|reut.rs|tf.to|path.com|';
    $pattern   .= 'ff.im|feedly.com|derstandard.at|vwoa.us|btr.mn|';
    $pattern   .= 'flic.kr|soc.li|tmi.me|read.bi|post.ly|onforb.es|rww.to';

    if(preg_match('/'.$pattern.'/i', $text, $matches)) {
        return true;
    }
}

function isBitLyUrl($text)
{
    $pattern = 'bit.ly';

    if(preg_match('/'.$pattern.'/i', $text, $matches)) {
        return true;
    }
}

function isTCoUrl($text)
{
    $pattern = 't.co';

    if(preg_match('/'.$pattern.'/i', $text, $matches)) {
        return true;
    }
}

/**
 * remove tinyurl.com URLs
 *
 * @param   string  $text
 * @return  string
 */
function removeTinyUrls($text)
{
    $pattern    = '(((http?)(s?)\:\/\/))?';
    $pattern   .= '(((tinyurl.com)\/))';
    $pattern   .= '[A-Za-z0-9][A-Za-z0-9.-]+(:\d+)?(\/[^ ]*)?';

    if(preg_match_all('/'.$pattern.'/i', $text, $matches)) {
        if(!empty($matches)) {
            foreach($matches AS $key => $value) {
                $text = str_ireplace($value, '', $text);
            }
        }
    }

    return $text;
}

/**
 * remove all URLs matching the specified pattern
 *
 * @param   string  $text
 * @param   string  $pattern
 * @return  string
 */
function removeUrlsByPattern($text, $pattern)
{
    $pattern    = '(((http?)(s?)\:\/\/))?';
    $pattern   .= '((('.$pattern.')\/))';
    $pattern   .= '[A-Za-z0-9][A-Za-z0-9.-]+(:\d+)?(\/[^ ]*)?';

    if(preg_match_all('/'.$pattern.'/i', $text, $matches)) {
        if(!empty($matches)) {
            foreach($matches AS $key => $value) {
                $text = str_ireplace($value, '', $text);
            }
        }
    }

    return $text;
}

/**
 * remove yfrog.ly URLs
 *
 * @param   string  $text
 * @return  string
 */
function removeYfrogUrls($text)
{
    $pattern    = '(((http?)(s?)\:\/\/))?';
    $pattern   .= '(((yfrog.com)\/))';
    $pattern   .= '[A-Za-z0-9][A-Za-z0-9.-]+(:\d+)?(\/[^ ]*)?';

    if(preg_match_all('/'.$pattern.'/i', $text, $matches)) {
        if(!empty($matches)) {
            foreach($matches AS $key => $value) {
                $text = str_ireplace($value, '', $text);
            }
        }
    }

    return $text;
}

/**
 * remove binged.it URLs
 *
 * @param   string  $text
 * @return  string
 */
function removeBingedItUrls($text)
{
    $pattern    = '(((http?)(s?)\:\/\/))?';
    $pattern   .= '(((binged.it)\/))';
    $pattern   .= '[A-Za-z0-9][A-Za-z0-9.-]+(:\d+)?(\/[^ ]*)?';

    if(preg_match_all('/'.$pattern.'/i', $text, $matches)) {
        if(!empty($matches)) {
            foreach($matches AS $key => $value) {
                $text = str_ireplace($value, '', $text);
            }
        }
    }

    return $text;
}

/**
 * determine if specified text is English
 *
 * @link    http://stackoverflow.com/questions/1550950/detect-chinese-multibyte-character-in-the-string
 * @param   string   $str
 * @return  boolean
 */
function is_english($str)
{
    if(preg_match('/[\x{4e00}-\x{9fa5}]+.*\-/u', $str, $matches)) {
        return false;
    } else {
        return true;
    }
}

/**
 * convert a unix timestamp to MySQL DATETIME
 *
 * @param   int     $timestamp
 * @return  string
 */
function timestamp_to_mysql_datetime($timestamp = null)
{
    $timestamp = is_null($timestamp) ? time() : $timestamp;
    return date('Y-m-d H:i:s', $timestamp);
}

/**
 * convert a unix timestamp to MySQL DATE
 *
 * @param   int     $timestamp
 * @return  string
 */
function timestamp_to_mysql_date($timestamp = null)
{
    $timestamp = is_null($timestamp) ? time() : $timestamp;
    return date('Y-m-d', $timestamp);
}

/**
 * convert a MySQL timestamp to a unix timestamp
 *
 * @param   int     $timestamp
 * @return  string
 */
function mysql_timestamp_to_unix_timestamp($timestamp = null)
{
    return strtotime($timestamp);
}

/**
 * extract digits from a string
 *
 * @param   string   $str
 * @return  int
 */
function extractDigits($str)
{
    if(preg_match('/(?P<digit>\d+)/', $str, $matches)) {
        return $matches['digit'];
    }
}

/**
 * flatten an array
 *
 * @link    http://chriswa.wordpress.com/2011/04/25/array_flatten-in-php/
 * @param   array   $array
 */
function flatten_array($array)
{
    return call_user_func_array('array_merge', $array);
}

/**
 * initialize the initial session values if
 * they are not stored in the end-user's session
 */
function setInitialSessionValues()
{

}

/**
 * determine the location of a word break in text
 *
 * @link    http://www.tek-tips.com/viewthread.cfm?qid=1508929
 * @param   array
 * @param   string
 * @return  array
 */
function getWordBreakByString($haystack, $string)
{
    $pattern    = '/(\b)\w+(\b)/imx';
    $result     = preg_match($pattern, $string, $match, PREG_OFFSET_CAPTURE);

    return array('before' => $match[1][1], 'after' => $match[2][1]);
}

/**
 * determine the location of a word break in text
 *
 * @link    http://www.tek-tips.com/viewthread.cfm?qid=1508929
 * @param   string
 * @return  array
 */
function getWordBreaks($string)
{
    $pattern    = '/\b(\w+)\b/imx';
    $result     = preg_match_all($pattern, $string, $matches, PREG_OFFSET_CAPTURE);

    return ($result) ? $matches[1] : false;
}

/**
 * remove multiple dots from the end of a string
 *
 * @param   string
 * @return  string
 */
function removeMultipleDotsFromEndofText($text)
{
    $text = trim($text, '.');

    if(preg_match('/\./', $text)) {
        $strlen = strlen($text);
        if(strpos($text, '.') == ($strlen - 2) AND $strlen == 3) {
            $text = $text.'.';
        }
    }

    return $text;
}

/**
 * determine if a string contains consecutive dots
 *
 * @param   string  $text
 * @return  boolean
 */
function containsConsecutiveDots($text)
{
    if(preg_match('/…/', $text)) {
        return true;
    }

    if(preg_match('/\.\./', $text)) {
        return true;
    }

    if(preg_match('/\.\.\./', $text)) {
        return true;
    }
}

/**
 * determine if a string contains characters
 *
 * @link    http://icfun.blogspot.com/2008/07/regex-to-match-same-consecutive.html
 * @param   string  $text
 * @return  boolean
 */
function containsConsecutiveChars($text)
{
    if(preg_match('/(.)\1{2}/i', $text)) {
        return true;
    }
}

/**
 * determine if a string contains brackets
 *
 * @param   string  $text
 * @return  boolean
 */
function containsBrackets($text)
{
    if(preg_match('/(/', $text)) {
        return true;
    }
}

/**
 * str_word_count does not count numbers
 * this is a workaround
 *
 * @param   string  $text
 * @return  int
 */
function strWordCount($text)
{
    return count(explode(' ', $text));
}

/**
 * calculate percent increase / decrease
 *
 * @link    http://www.onemathematicalcat.org/algebra_book/online_problems/calc_percent_inc_dec.htm
 * @link    http://www.google.com/search?q=percent+increase+from+zero
 * @param   int     $start
 * @param   int     $end
 * @return  string
 */
function percentChange($start, $end)
{
    $start  = (int)$start;
    $end    = (int)$end;

    if($start == 0) {
        // infinity
        return 'INFINITY';
    }

    if($start < $end) {
        $change = (($start - $end) / $start) * 100;
        // remove the negative sign
        $change = str_replace('-', '', $change);
    } else {
        $change = (($end - $start) / $start) * 100;
    }

    $change = round($change, 2);

    return $change;
}

/**
 * determine if a number is negative
 *
 * @param   int     $number
 * @return  boolean
 */
function isNegativeNumer($number)
{
    if($number < 0) {
        return true;
    }
}

/**
 * determine if a string is JSON
 *
 * @param   string  $string
 * @return  boolean
 */
function is_json($string)
{
    return (is_string($string) && is_object(json_decode($string))) ? true : false;
}

/**
 * convert seconds to days
 *
 * @param   int     $seconds
 * @return  int
 */
function secondsToDays($seconds)
{
    // 86400 seconds = 24 hours
    $data = ($seconds / 86400);
    $data = floor($data);

    return $data;
}

/**
 * replace a word using preg_replace
 *
 * @link    http://chumby.net/?p=44
 * @param   string  $needle
 * @param   string  $replacement
 * @param   string  $haystack
 * @param   boolean $caseInsensitive
 * @return  string  $haystack
 */
function str_replace_word($needle, $replacement, $haystack, $caseInsensitive = true)
{
    $needle     = str_replace('/', '\/', $needle);
    $needle     = str_replace('(', '\(', $needle);
    $needle     = str_replace(')', '\)', $needle);

    if($caseInsensitive) {
        $pattern = "/\b".$needle."\b/i";
    } else {
        $pattern = "/\b".$needle."\b";
    }
    $haystack   = preg_replace($pattern, $replacement, $haystack);

    return $haystack;
}

/**
 * return JSONP data
 *
 * @param   string  $callback
 * @param   string  $json
 * @return  string
 */
function outputJsonP($callback, $json)
{
    return $callback.'('.$json.');';
}

/**
 * signal handler function
 *
 * @link    http://www.php.net/manual/en/function.pcntl-signal.php
 * @link    http://tuxradar.com/practicalphp/16/1/6
 * @link    http://www.php.net/manual/en/pcntl.constants.php
 * @link    http://www.php.net/manual/en/pcntl.example.php
 * @param   string  $signo
 */
function signal_handler($signo)
{
    switch ($signo) {
        case SIGCHLD:
            while (pcntl_waitpid(0, $status) != -1) {
                $status = pcntl_wexitstatus($status);
                echo "Child ".$status." completed\n";
            }

            exit;
            break;

        case SIGTERM:
            // handle shutdown tasks
            exit;
            break;

         case SIGHUP:
            // handle restart tasks
            break;

         case SIGUSR1:
            echo "Caught SIGUSR1...\n";
            break;

         default:
             // handle all other signals
     }
}

/**
 * determine the time elapsed since a Unix timestamp
 *
 * @param   int     $timestamp
 * @return  string
 */
function elapsedTime($timestamp, $returnAgo = false)
{
    $difference = time() - $timestamp;

    // if more than a year ago
    if ($difference >= 60*60*24*365) {
        $int    = intval($difference / (60*60*24*365));
        $s      = ($int > 1) ? 's' : '';
        $r      = $int . ' year' . $s;
    // if more than five weeks ago
    } elseif ($difference >= 60*60*24*7*5) {
        $int    = intval($difference / (60*60*24*30));
        $s      = ($int > 1) ? 's' : '';
        $r      = $int . ' month' . $s;
    // if more than a week ago
    } elseif ($difference >= 60*60*24*7) {
        $int    = intval($difference / (60*60*24*7));
        $s      = ($int > 1) ? 's' : '';
        $r      = $int . ' week' . $s;
    // if more than a day ago
    } elseif ($difference >= 60*60*24) {
        $int    = intval($difference / (60*60*24));
        $s      = ($int > 1) ? 's' : '';
        $r      = $int . ' day' . $s;
    // if more than an hour ago
    } elseif ($difference >= 60*60) {
        $int    = intval($difference / (60*60));
        $s      = ($int > 1) ? 's' : '';
        $r      = $int . ' hour' . $s;
    // if more than a minute ago
    } elseif($difference >= 60) {
        $int    = intval($difference / (60));
        $s      = ($int > 1) ? 's' : '';
        $r      = $int . ' minute' . $s;
    // if less than a minute ago
    } else {
        $r = 'moments';
    }

    if($returnAgo) {
        $r .= ' ago';
    }

    return $r;
}

function get_elapsed_time($ts, $datetime = true)
{
    if($datetime) {
        $ts = date('U', strtotime($ts));
    }

    $mins   = floor((time() - $ts) / 60);
    $hours  = floor($mins / 60);
    $mins  -= $hours * 60;
    $days   = floor($hours / 24);
    $hours -= $days * 24;
    $months = floor($days / 30);
    $weeks  = floor($days / 7);
    $days  -= $weeks * 7;
    $t      = '';

    if ($months > 0) {
        return $months.' month' . ($months > 1 ? 's ago' : ' ago');
    }

    if ($weeks > 0) {
        return $weeks.' week' . ($weeks > 1 ? 's ago' : ' ago');
    }

    if ($days > 0) {
        return $days.' day' . ($days > 1 ? 's ago' : ' ago');
    }

    if ($hours > 0) {
        return $hours. ' hour' . ($hours > 1 ? 's ago' : ' ago');
    }

    if ($mins > 0) {
        return $mins. ' min' . ($mins > 1 ? 's ago' : ' ago');
    }

    return '< 1 min';
}

/**
 * return the first element of an array
 *
 * @param   array    $array
 * @return  mixed
 */
function fetchFirstArrayElement($array)
{
    return array_shift( $array );
}

/**
 * Replace all linebreaks with one whitespace.
 *
 * @link    http://www.php.net/manual/en/function.str-replace.php#97374
 * @access  public
 * @param   string    $string The text to be processed.
 * @return  string   The given text without any linebreaks.
 */
function remove_newlines($string)
{
    return (string)str_replace(array("\r", "\r\n", "\n"), '', $string);
}

/**
 * determine if a script is running by name
 *
 * @param   string    $scriptName
 * @return  boolean
 */
function isScriptRunningByName($scriptName)
{
    $psArray    = array();
    $output     = array();
    $return     = '';
    $ps         = exec('pgrep -f '.$scriptName, $psArray, $return);
    $count      = count($psArray);

    if(empty($psArray) OR $count < 1) {
        return false;
    } else {
        return true;
    }
}

/**
 * determine if a script is running with specific command-line paramters
 *
 * @param   string    $scriptName
 * @return  boolean
 */
function isScriptRunningWithArgs($scriptName)
{
    $psArray    = array();
    $output     = array();
    $return     = '';
    $ps         = exec('ps -fp $(pgrep -d, -x php)', $psArray, $return);

    if(!empty($psArray)) {
        foreach($psArray AS $key => $value) {
            if( preg_match('/'.$scriptName.'/', $value) ) {
                return true;
            }
        }
    }
}

function fetchScriptRunCount($scriptName)
{
    $psArray = fetchScriptPids($scriptName);
    if( !empty($psArray) ) {
        $myPid = getmypid();
        // remove self
        foreach($psArray AS $key => $value) {
            if($value == $myPid) {
                unset($psArray[$key]);
            }
        }

        return count($psArray);
        
    } else {
        return 0;
    }
}

function fetchScriptPids($scriptName)
{
    $psArray    = array();
    $output     = array();
    $return     = '';
    $ps         = exec("ps -eo pid,command | grep ".$scriptName." | grep -v grep | grep -v /bin/sh | grep -v 'sh -c' | awk '{print $1}'", $psArray, $return);

    return $psArray;
}

function isNegative($int)
{
    if(preg_match('/-/', $int)) {
        return true;
    }
}

/**
 * determine if a string is Latin
 *
 * @param   string    $string
 * @return  boolean
 */
function isLatin($string)
{   
    if(preg_match('/\p{Latin}+/u', $string)) {
        return true;
    }
}

function isChinese($string)
{
    if(preg_match('/\p{Han}+/u', $string)) {
        return true;
    }
}

/**
 * a recursive function which adds the values of two multidimensional
 * arrays with the same key structure:
 *
 * @author  George Pligor
 * @link    http://www.php.net/manual/en/function.array-sum.php#104222
 * @param   array   $left
 * @param   array   $right
 * @return  array
 */
function multiDimArrayAdd(& $left, $right)
{
    if(is_array($left) && is_array($right)) {
        foreach($left as $key => $val) {
            if( is_array($val) ) {
                multiDimArrayAdd($left[$key], $right[$key]);
            }
            $left[$key] += $right[$key];
        }
    }
}

/**
 * determine if a URL exists within a string
 *
 * @link    http://daringfireball.net/2009/11/liberal_regex_for_matching_urls
 * @link    http://stackoverflow.com/questions/3539009/preg-match-to-domain-tld
 * @param   string  $string
 * @return  boolean
 */
function containsUrl($string)
{
    if(preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $string, $match)) {
        return true;
    }

    if (preg_match('/^[-a-z0-9]+\.a[cdefgilmnoqrstuwxz]|b[abdefghijmnorstvwyz]|c[acdfghiklmnoruvxyz]|d[ejkmoz]|e[cegrstu]|f[ijkmor]|g[abdefghilmnpqrstuwy]|h[kmnrtu]|i[delmnoqrst]|j[emop]|k[eghimnprwyz]|l[abcikrstuvy]|m[acdeghklmnopqrstuvwxyz]|n[acefgilopruz]|om|p[aefghklmnrstwy]|qa|r[eosuw]|s[abcdeghijklmnortuvyz]|t[cdfghjklmnoprtvwz]|u[agksyz]|v[aceginu]|w[fs]|y[et]|z[amw]|biz|cat|co|com|edu|gov|int|mil|net|org|pro|tel|aero|arpa|asia|to|tv|coop|info|jobs|mobi|name|museum|travel|arpa|xn--[a-z0-9]+$/', strtolower($string))) {
        return true;
    }

}

/**
 * remove an item from an array by value
 *
 * @link    http://dev-tips.com/featured/remove-an-item-from-an-array-by-value
 * @param   string  $value
 * @param   array   $array
 * @return  array
 */
function removeArrayElementByValue($value, $array)
{
    return array_diff( $array, array($value) );
}

/**
 * determine the run environment based on the server IP
 *
 * @return  string
 */
function determineRunEnvironment()
{
    $demo   = array('10.1.0.203');
    $live   = array('10.1.0.204');
    $dev    = array('10.1.0.205');
    $ip     = fetchLocalServerIP();

    if(in_array($ip, $demo)) {
        return 'DEMO';
    }

    if(in_array($ip, $dev)) {
        return 'DEV';
    }

    if(in_array($ip, $live)) {
        return 'LIVE';
    }

    return 'UNKNOWN';
}

/**
 * set the run environment
 */
function setRunEnvironment($env)
{
    if( !defined('RUN_ENV') ) {
        define('RUN_ENV', $env);
    }
}

function fetchLocalServerIP()
{
    $ip = ( isset($_SERVER['SERVER_ADDR']) ) ? $_SERVER['SERVER_ADDR'] : fetchLocalServerIPViaBash();
    return $ip;
}

function fetchLocalServerIPViaBash()
{
    exec("ifconfig eth0 | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'", $output, $return);
    if( !empty($output) ) {
        return $output[0];
    }
}

function determineUserLocale()
{
    $locale = new Zend_Locale();
    return $locale->getLanguage();
}
