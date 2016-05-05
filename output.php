<?php
$t = '{"analysis": {
      "analyzer": {
        "my_lowercaser": {
          "tokenizer": "icu_tokenizer",
          "filter":  [ "icu_normalizer" ] 
        }
      }
    }}';
var_dump(json_decode($t));
