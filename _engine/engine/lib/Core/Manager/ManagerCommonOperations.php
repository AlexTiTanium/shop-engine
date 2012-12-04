<?php

namespace lib\Core\Manager;

use lib\EngineExceptions\SystemException;
use lib\Debugger\Debugger;
use lib\Core\IncluderService;
use lib\View\View;

class ManagerCommonOperations {

   private $translitAlphabet = array(
     "А"=>"d","Б"=>"b","В"=>"v","Г"=>"g",
     "Д"=>"d","Е"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
     "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
     "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
     "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
     "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
     "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
     "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
     "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
     "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
     "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
     "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
     "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
     " "=> "_", "."=> "", "/"=> "_"
   );

  /**
   * Convert rus to lat
   *
   * @param $string
   * @return string
   */
  public function translitEncode($string) {

    return strtr($string, $this->translitAlphabet);
  }

  /**
   * ManagerCommonOperations::percent()
   *
   * @param int - summ
   * @param  int - percent
   * @return int - round(number, 2)
   */
  public function percent($sum, $percent) {
    return round(($sum * $percent) / 100, 2);
  }

  /**
   * ManagerCommonOperations::uniqueRand()
   *
   * Возвращает  массив рандомных уникальных записей
   *
   * @param int $n number of random numbers to return in the array
   * @param int $min minimum number
   * @param int $max maximum numbe
   *
   * @return array
   */
  public function uniqueRand($n, $min = 0, $max = null) {
    if ($n == 0) {
      return array();
    }

    if ($max === null) {
      $max = getrandmax();
    }

    $array = range($min, $max);
    $keys = (array)array_rand($array, $n);

    $return = array();
    foreach ($keys as $key) {
      $return[] = $array[$key];
    }

    return $return;
  }

  /**
   * ManagerCommonOperations::arrayToCsv()
   *
   * Переводит массив в CSV
   *
   * @param array $array
   * @param bool $header_row
   * @param string $col_sep
   * @param string $row_sep
   * @param string $qut
   *
   * @return string in csv format
   */
  public function arrayToCsv(array $array, $header_row = true, $col_sep = ";", $row_sep = "\n", $qut = '"') {

    $output = '';

    //Header row.
    if ($header_row) {
      foreach ($array[0] as $key => $val) {
        //Escaping quotes.
        $key = str_replace($qut, "$qut$qut", $val);
        $output .= "$col_sep$qut$key$qut";
      }
      $output = substr($output, 1) . "\n";
      unset($array[0]);
    }
    //Data rows.
    foreach ($array as $key => $val) {
      $tmp = '';
      if (empty($val)) {
        $output .= $row_sep;
        continue;
      }

      foreach ($val as $cell_key => $cell_val) {
        //Escaping quotes.
        $cell_val = str_replace($qut, "$qut$qut", $cell_val);
        $tmp .= "$col_sep$qut$cell_val$qut";
      }
      $output .= substr($tmp, 1) . $row_sep;
    }

    return $output;
  }

  /**
   * ManagerCommonOperations::getThisWeek()
   *
   * Возрашает масив с 2-мя элементами дата от начала недели и конец недели
   *
   * @param bool $withNullTime
   * @return array
   */
  public function getThisWeek($withNullTime = true) {

    $mask = $withNullTime ? 'Y-m-d 00:00:00' : 'Y-m-d';

    if (date('N') === '1') {
      $firstDate = date($mask, time());
      $secondDate = date($mask, strtotime('next Monday'));
    } else {
      $firstDate = date($mask, strtotime('last Monday'));
      $secondDate = date($mask, strtotime('next Monday'));
    }

    return array($firstDate, $secondDate);
  }

  /**
   * ManagerCommonOperations::getLastWeek()
   *
   * Возрашает масив с 2-мя элементами дата от начала недели и конец недели
   *
   * @param bool $withNullTime
   * @return array
   */
  public function getLastWeek($withNullTime = true) {

    $mask = $withNullTime ? 'Y-m-d 00:00:00' : 'Y-m-d';

    if (date('N') === '1') {
      $firstDate = date($mask, strtotime('now-1 week'));
      $secondDate = date($mask, strtotime('now'));
    } else {
      $firstDate = date($mask, strtotime('last Monday-1 week'));
      $secondDate = date($mask, strtotime('last Monday'));
    }

    return array($firstDate, $secondDate);
  }

  /**
   * Check if ip in ranges
   *
   * $ranges = array(
   *  '212.118.48.*',
   *  '212.158.173.*',
   *  '91.200.28.*',
   *  '91.227.52.*'
   * );
   *
   * Data::ipInRanges('192.168.1.1', $ranges);
   *
   * @static
   * @param $ip
   * @param array $ranges
   * @return bool
   */
  public static function ipInRanges($ip, array $ranges) {

    foreach ($ranges as $range) {
      if (self::ipInRange($ip, $range)) {
        return true;
      }
    }

    return false;
  }

  /**
   * ip_in_range.php - Function to determine if an IP is located in a
   *                   specific range as specified via several alternative
   *                   formats.
   *
   * Network ranges can be specified as:
   * 1. Wildcard format:     1.2.3.*
   * 2. CIDR format:         1.2.3/24  OR  1.2.3.4/255.255.255.0
   * 3. Start-End IP format: 1.2.3.0-1.2.3.255
   *
   * Return value BOOLEAN : ip_in_range($ip, $range);
   *
   * Copyright 2008: Paul Gregg <pgregg@pgregg.com>
   * 10 January 2008
   * Version: 1.2
   *
   * Source website: http://www.pgregg.com/projects/php/ip_in_range/
   * Version 1.2
   *
   * This software is Donationware - if you feel you have benefited from
   * the use of this tool then please consider a donation. The value of
   * which is entirely left up to your discretion.
   * http://www.pgregg.com/donate/
   *
   * Please do not remove this header, or source attibution from this file.
   *
   * This function takes 2 arguments, an IP address and a "range" in several
   * different formats.
   * Network ranges can be specified as:
   * 1. Wildcard format:     1.2.3.*
   * 2. CIDR format:         1.2.3/24  OR  1.2.3.4/255.255.255.0
   * 3. Start-End IP format: 1.2.3.0-1.2.3.255
   * The function will return true if the supplied IP is within the range.
   * Note little validation is done on the range inputs - it expects you to
   * use one of the above 3 formats.
   **/
  public static function ipInRange($ip, $range) {
    /** decbin32
     * In order to simplify working with IP addresses (in binary) and their
     * netmasks, it is easier to ensure that the binary strings are padded
     * with zeros out to 32 characters - IP addresses are 32 bit numbers
     **/
    /*
    function decBin32($dec) {
      return str_pad(decbin($dec), 32, '0', STR_PAD_LEFT);
    }*/

    if (strpos($range, '/') !== false) {
      // $range is in IP/NETMASK format
      list($range, $netmask) = explode('/', $range, 2);
      if (strpos($netmask, '.') !== false) {
        // $netmask is a 255.255.0.0 format
        $netmask = str_replace('*', '0', $netmask);
        $netmask_dec = ip2long($netmask);
        return ((ip2long($ip) & $netmask_dec) == (ip2long($range) & $netmask_dec));
      } else {
        // $netmask is a CIDR size block
        // fix the range argument
        $x = explode('.', $range);
        while (count($x) < 4) $x[] = '0';
        list($a, $b, $c, $d) = $x;
        $range = sprintf("%u.%u.%u.%u", empty($a) ? '0' : $a, empty($b) ? '0' : $b, empty($c) ? '0' : $c, empty($d) ? '0' : $d);
        $range_dec = ip2long($range);
        $ip_dec = ip2long($ip);

        # Strategy 1 - Create the netmask with 'netmask' 1s and then fill it to 32 with 0s
        #$netmask_dec = decBin32(str_pad('', $netmask, '1') . str_pad('', 32-$netmask, '0'));

        # Strategy 2 - Use math to create it
        $wildcard_dec = pow(2, (32 - $netmask)) - 1;
        $netmask_dec = ~$wildcard_dec;

        return (($ip_dec & $netmask_dec) == ($range_dec & $netmask_dec));
      }
    } else {
      // range might be 255.255.*.* or 1.2.3.0-1.2.3.255
      if (strpos($range, '*') !== false) { // a.b.*.* format
        // Just convert to A-B format by setting * to 0 for A and 255 for B
        $lower = str_replace('*', '0', $range);
        $upper = str_replace('*', '255', $range);
        $range = "$lower-$upper";
      }

      if (strpos($range, '-') !== false) { // A-B format
        list($lower, $upper) = explode('-', $range, 2);
        $lower_dec = (float)sprintf("%u", ip2long($lower));
        $upper_dec = (float)sprintf("%u", ip2long($upper));
        $ip_dec = (float)sprintf("%u", ip2long($ip));
        return (($ip_dec >= $lower_dec) && ($ip_dec <= $upper_dec));
      }

      throw new SystemException('Range argument is not in 1.2.3.4/24 or 1.2.3.4/255.255.255.0 format');
    }
  }

}