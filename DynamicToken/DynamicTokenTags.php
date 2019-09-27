<?php

namespace Statamic\Addons\DynamicToken;

use Statamic\Extend\Tags;

class DynamicTokenTags extends Tags
{
  /**
   * The {{ dynamic_token }} tag
   * inserts a script which will add tokens to all forms which have an input with a name="_token"
   * refreshes said token every 15 minutes
   * @return string
   */
  public function index()
  {
    $route = $this->actionUrl('/refresh');
    $selector = $this->getConfig('selector', 'form input[name="_token"]');
    $minutes = $this->getConfigInt('refresh_timer', 15);

    return "
        <script>
        if (document.querySelectorAll('{$selector}').length > 0) {
          //add a ponyfill for IE11
          if (window.NodeList && !NodeList.prototype.forEach) {
            NodeList.prototype.forEach = function(callback, thisArg) {
              thisArg = thisArg || window;
              for (var i = 0; i < this.length; i++) {
                callback.call(thisArg, this[i], i, this);
              }
            };
          }

          // simple httprequest
          function httpGetAsync(theUrl, callback) {
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function() {
              if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
              callback(xmlHttp.responseText);
            };
            xmlHttp.open('GET', theUrl, true); // true for asynchronous
            xmlHttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xmlHttp.send(null);
          }

          function setToken(token) {
            document
              .querySelectorAll('{$selector}')
              .forEach(function(item) {
                item.value = token;
              });
          }

          function updateToken() {
            httpGetAsync('{$route}', setToken);
          }

          updateToken();

          setInterval(updateToken, {$minutes} * 60 * 1000); // Every 15 minutes.
        }
      </script>";
  }
}
