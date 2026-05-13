<?php
if (!class_exists('DOMDocument')) {
    class DOMDocument {
        public $formatOutput = false;
        public function loadHTML($s, $o = 0) { return true; }
        public function getElementsByTagName($n) { return new class { public $length = 0; public function item($i) { return null; } }; }
        public function saveHTML($n = null) { return ''; }
        public function createElement($n, $v = '') { return new class {}; }
    }
    class DOMXPath {
        public function __construct($d) {}
        public function query($e) { return new class { public $length = 0; public function item($i) { return null; } }; }
    }
}
