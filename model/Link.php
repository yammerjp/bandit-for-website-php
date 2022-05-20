<?php

final class Link {
  public static function render()
  {
    echo "
      <div style=\"padding-top: 48px; text-align: center;\">
      <hr>
      links:
      <a href=\"/index.php\">index</a> /
      <a href=\"/summary.php\">summary</a> / 
      <a href=\"/phpinfo.php\">phpinfo</a> /
      <a href=\"/graph.php\">graph</a>
      </div>
    ";
  }
}
