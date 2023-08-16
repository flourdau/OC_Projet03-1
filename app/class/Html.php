<?php
namespace Gen;

/*
* HTML Management
*/
class Html{
  static function link($parram, $title){
    if ($parram)
      return ("<a {$parram}>{$title}</a>\n");
    return (NULL);
  }

  static function surround($tag, $parram, $html){
    if ($tag && $html)
      return ("<{$tag} {$parram}>{$html}</{$tag}>\n");
    else if ($html)
      return ($html);
    return (NULL);
  }

  static function input($params, $b_end = NULL){
    return ("<input $params>$b_end");
  }

  static function submit($value = "Send"){
    return ("<button type=\"submit\">$value</button>\n");
  }
}
