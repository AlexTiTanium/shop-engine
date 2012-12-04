<?php

namespace lib\Stemer;


class Stemer {

  private $VERSION = "0.02";
  private $Stem_Caching = 0;
  private $Stem_Cache = array();
  private $VOWEL = '/аеиоуыэюя/u';
  private $PERFECTIVEGROUND = '/((ив|ивши|ившись|ыв|ывши|ывшись)|((?<=[ая])(в|вши|вшись)))$/u';
  private $REFLEXIVE = '/(с[яь])$/u';
  private $ADJECTIVE = '/(ее|ие|ые|ое|ими|ыми|ей|ий|ый|ой|ем|им|ым|ом|его|ого|ему|ому|их|ых|ую|юю|ая|яя|ою|ею)$/u';
  private $PARTICIPLE = '/((ивш|ывш|ующ)|((?<=[ая])(ем|нн|вш|ющ|щ)))$/u';
  private $VERB = '/((ила|ыла|ена|ейте|уйте|ите|или|ыли|ей|уй|ил|ыл|им|ым|ен|ило|ыло|ено|ят|ует|уют|ит|ыт|ены|ить|ыть|ишь|ую|ю)|((?<=[ая])(ла|на|ете|йте|ли|й|л|ем|н|ло|но|ет|ют|ны|ть|ешь|нно)))$/u';
  private $NOUN = '/(а|ев|ов|ие|ье|е|иями|ями|ами|еи|ии|и|ией|ей|ой|ий|й|иям|ям|ием|ем|ам|ом|о|у|ах|иях|ях|ы|ь|ию|ью|ю|ия|ья|я)$/u';
  private $RVRE = '/^(.*?[аеиоуыэюя])(.*)$/u';
  private $DERIVATIONAL = '/[^аеиоуыэюя][аеиоуыэюя]+[^аеиоуыэюя]+[аеиоуыэюя].*(?<=о)сть?$/u';

  /**
   * StemerBase::s()
   *
   * @param mixed $s
   * @param mixed $re
   * @param mixed $to
   * @return bool
   */
  private function s(&$s, $re, $to){
    $orig = $s;
    $s = preg_replace($re, $to, $s);

    return $orig !== $s;
  }

  /**
   * StemerBase::m()
   *
   * @param mixed $s
   * @param mixed $re
   * @return int
   */
  private function m($s, $re){ return preg_match($re, $s); }

  /**
   * StemerBase::__construct()
   *
   * @param mixed $words
   * @return array
   */
  static public function stem($words){

    $wordsArr = array();
    $wordsArrEnd = array();

    $wordsArr = explode(' ', $words);

    foreach($wordsArr as $value) {
      $Stemer = new Stemer();
      $wordsArrEnd[] = $Stemer->stem_word($value);
    }

    return $wordsArrEnd;
  }

  /**
   * StemerBase::stem_word()
   *
   * @param mixed $word
   * @return mixed|string
   */
  private function stem_word($word){

    $word = mb_strtolower($word, 'UTF-8');
    $word = str_replace('ё', 'е', $word);
    # Check against cache of stemmed words
    if($this->Stem_Caching && isset($this->Stem_Cache[$word])) {
      return $this->Stem_Cache[$word];
    }

    $stem = $word;
    do {
      if(!preg_match($this->RVRE, $word, $p)) {
        break;
      }
      $start = $p[1];
      $RV = $p[2];

      if(!$RV) break;

      # Step 1
      if(!$this->s($RV, $this->PERFECTIVEGROUND, '')) {
        $this->s($RV, $this->REFLEXIVE, '');

        if($this->s($RV, $this->ADJECTIVE, '')) {
          $this->s($RV, $this->PARTICIPLE, '');
        } else {
          if(!$this->s($RV, $this->VERB, '')) $this->s($RV, $this->NOUN, '');
        }
      }

      # Step 2
      $this->s($RV, '/и$/u', '');

      # Step 3
      if($this->m($RV, $this->DERIVATIONAL)) {
        $this->s($RV, '/ость?$/u', '');
      }

      # Step 4
      if(!$this->s($RV, '/ь$/u', '')) {
        $this->s($RV, '/ейше?/u', '');
        $this->s($RV, '/нн$/u', 'н');
      }

      $stem = $start . $RV;
    } while(false);
    if($this->Stem_Caching) $this->Stem_Cache[$word] = $stem;

    return $stem;
  }

  /**
   * StemerBase::stem_caching()
   *
   * @param mixed $parm_ref
   * @return int
   */
  private function stem_caching($parm_ref){

    $caching_level = @$parm_ref['-level'];
    if($caching_level) {
      if(!$this->m($caching_level, '/^[012]$/')) {
        die(__CLASS__ . "::stem_caching() - Legal values are '0','1' or '2'. '$caching_level' is not a legal value");
      }
      $this->Stem_Caching = $caching_level;
    }
    return $this->Stem_Caching;
  }

  /**
   * StemerBase::clear_stem_cache()
   *
   * @return void
   */
  private function clear_stem_cache(){
    $this->Stem_Cache = array();
  }

}