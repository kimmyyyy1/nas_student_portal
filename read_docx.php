<?php $zip = new ZipArchive; if ($zip->open($argv[1])) { echo strip_tags($zip->getFromName("word/document.xml")); }
